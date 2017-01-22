<?php


namespace ProVision\Minifier;


use Illuminate\Support\Facades\Config;

class Minifier
{
    private $html;

    function __construct($html)
    {

        if (!Config::get('provision_minifier.enable', false)) {
            return $html;
        }

        $this->html = $html;

        if (Config::get('provision_minifier.remove_comments', false)) {
            $this->removeComments();
        }

        if (Config::get('provision_minifier.remove_lines', false)) {
            $this->html = $this->removeLines($this->html);
        }
    }

    public function get()
    {
        return $this->html;
    }

    /**
     * Remove comments
     */
    private function removeComments()
    {

        // Remove htmlcomments
        $additionaly = array(
            '/<!--[^\[](.*?)[^\]]-->/s' => '',
            '/(?:(?:\/\*(?:[^*]|(?:\*+[^*\/]))*\*+\/)|(?:(?<!\:|\\\|\\\' | ")\/\/.*))/' => '',
        );

        $this->html = preg_replace(array_keys($additionaly), array_values($additionaly), $this->html);
    }

    /**
     * Remove lines
     */
    private function removeLines($buffer)
    {
        /**
         * To remove useless whitespace from generated HTML, except for Javascript.
         * [Regex Source]
         * https://github.com/bcit-ci/codeigniter/wiki/compress-html-output
         * http://stackoverflow.com/questions/5312349/minifying-final-html-output-using-regular-expressions-with-codeigniter
         */
        $regexRemoveWhiteSpace = '%# Collapse ws everywhere but in blacklisted elements.
        (?>             # Match all whitespaces other than single space.
          [^\S ]\s*     # Either one [\t\r\n\f\v] and zero or more ws,
        | \s{2,}        # or two or more consecutive-any-whitespace.
        ) # Note: The remaining regex consumes no text at all...
        (?=             # Ensure we are not in a blacklist tag.
          (?:           # Begin (unnecessary) group.
            (?:         # Zero or more of...
              [^<]++    # Either one or more non-"<"
            | <         # or a < starting a non-blacklist tag.
              (?!/?(?:textarea|pre)\b)
            )*+         # (This could be "unroll-the-loop"ified.)
          )             # End (unnecessary) group.
          (?:           # Begin alternation group.
            <           # Either a blacklist start tag.
            (?>textarea|pre)\b
          | \z          # or end of file.
          )             # End alternation group.
        )  # If we made it here, we are not in a blacklist tag.
        %ix';
        $regexRemoveWhiteSpace = '%(?>[^\S ]\s*| \s{2,})(?=(?:(?:[^<]++| <(?!/?(?:textarea|pre)\b))*+)(?:<(?>textarea|pre)\b|\z))%ix';
        $re = '%# Collapse whitespace everywhere but in blacklisted elements.
        (?>             # Match all whitespans other than single space.
          [^\S ]\s*     # Either one [\t\r\n\f\v] and zero or more ws,
        | \s{2,}        # or two or more consecutive-any-whitespace.
        ) # Note: The remaining regex consumes no text at all...
        (?=             # Ensure we are not in a blacklist tag.
          [^<]*+        # Either zero or more non-"<" {normal*}
          (?:           # Begin {(special normal*)*} construct
            <           # or a < starting a non-blacklist tag.
            (?!/?(?:textarea|pre|script)\b)
            [^<]*+      # more non-"<" {normal*}
          )*+           # Finish "unrolling-the-loop"
          (?:           # Begin alternation group.
            <           # Either a blacklist start tag.
            (?>textarea|pre|script)\b
          | \z          # or end of file.
          )             # End alternation group.
        )  # If we made it here, we are not in a blacklist tag.
        %Six';
        // $new_buffer = preg_replace('/<!--(.*|\n)-->/Uis', " ", sanitize_output($buffer));
        // $new_buffer = preg_replace('/\s+/', " ", sanitize_output($new_buffer));
        $new_buffer = mb_ereg_replace($regexRemoveWhiteSpace, " ", $this->sanitize_output($buffer));
        // We are going to check if processing has working
        if ($new_buffer === null) {
            $new_buffer = $buffer;
        }

        return str_ireplace('> <', '><', $new_buffer);

    }

    private function sanitize_output($buffer)
    {
        $search = array(
            '/\>[^\S ]+/s', // strip whitespaces after tags, except space
            '/[^\S ]+\</s', // strip whitespaces before tags, except space
            '/(\s)+/s', // shorten multiple whitespace sequences
        );
        $replace = array(
            '>',
            '<',
            '\\1'
        );
        $buffer = preg_replace($search, $replace, $buffer);
        return $buffer;
    }
}