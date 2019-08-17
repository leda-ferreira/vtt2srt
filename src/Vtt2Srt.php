<?php
/*
 * Copyright (C) 2015 Leda
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 */

namespace ledat;


class Vtt2Srt
{
    private $input_file;
    private $output_file;
    public function __construct($input_file, $output_file)
    {
        $this->input_file = $input_file;
        $this->output_file = $output_file;
    }
    public function run()
    {
        $contents = file_get_contents($this->input_file);
        if ($contents === false) {
            $message = "Error: Failed to read '{$this->input_file}'.";
            throw new Exception($message);
        }
        $output = $this->convert($contents);
        $result = file_put_contents($this->output_file, $output);
        if ($result === false) {
            $message = "Error: Failed to write to '{$this->output_file}'.";
            throw new Exception($message);
        }
        return 1;
    }
    private function removeHeader($lines)
    {
        // removes the WEBVTT header
        // header is lines before the first empty line
        // there are more than One line
        $state = 0;
        $ret = [];
        foreach ($lines as $line) {
            if (trim($line) === "") {
                $state = 1;
                continue;
            }
            if ($state) {
                $ret[] = $line;
            }
        }
        return $ret;
    }
    private function convert($contents)
    {
        $lines = $this->split($contents);
        $lines = $this->removeHeader($lines);
        $output = '';
        $i = 0;
        foreach ($lines as $line) {
            /*
             * at last version subtitle numbers are not working
             * as you can see that way is trustful than older
             *
             * */
            $pattern1 = '(\d{2}):(\d{2}):(\d{2})\.(\d{3})'; // '01:52:52.554'
            $pattern2 = '(\d{2}):(\d{2})\.(\d{3})'; // '00:08.301'
            /**
             * Well, it seems that the time line is far more complex than we think before.
             * For example: 00:00:00.060 --> 00:00:04.490 align:start position:0%
             * so we trim the modifiers after time line
             */
            $modifyPattern = '/ [a-zA-Z]\w*:\S+/';
            /**
             * vtt allow inner time tag, for example:
             * there<00:00:00.359><c> we</c><00:00:00.420><c> go</c>
             * we add ^ to avoid this preg match these inner time tag
             */
            $m1 = preg_match("/^$pattern1/", $line);
            if (is_numeric($m1) && $m1 > 0) {
                $i++;
                $output .= PHP_EOL . $i; // we'd better add an empty line, to make Aegisub happy
                $output .= PHP_EOL;
                $line = preg_replace("/$pattern1/", '$1:$2:$3,$4', $line);
                $line = preg_replace($modifyPattern, '', $line);
            } else {
                $m2 = preg_match("/^$pattern2/", $line);
                if (is_numeric($m2) && $m2 > 0) {
                    $i++;
                    $output .= PHP_EOL . $i;
                    $output .= PHP_EOL;
                    $line = preg_replace("/$pattern2/", '00:$1:$2,$3', $line);
                    $line = preg_replace($modifyPattern, '', $line);
                }
            }
            /**
             * we trim all inner tag because time tag is very annoying,
             * maybe we will convert tag perfectly in the future.
             */
            $output .= strip_tags($line) . PHP_EOL;
        }
        return $output;
    }
    private function split($contents)
    {
        $lines = explode("\n", $contents);
        if (count($lines) === 1) {
            $lines = explode("\r\n", $contents);
            if (count($lines) === 1) {
                $lines = explode("\r", $contents);
            }
        }
        return $lines;
    }
}
