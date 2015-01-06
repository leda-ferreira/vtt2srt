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

$vtt2srt_class = implode(DIRECTORY_SEPARATOR, array(dirname(__DIR__), 'src', 'Vtt2Srt.php'));
include $vtt2srt_class;

class Main
{

    public static function run()
    {
        if ($_SERVER['argc'] !== 3) {
            echo 'Usage: php vtt2srt <vtt file> <new srt file>', PHP_EOL;
            exit(1);
        }

        $input_file = $_SERVER['argv'][1];
        if (!(file_exists($input_file) && is_file($input_file))) {
            echo "Error: File '{$input_file}' does not exist or is not a regular file.", PHP_EOL;
            exit(1);
        }

        $output_file = $_SERVER['argv'][2];

        try {
            $vtt2srt = new \Ledat\Vtt2Srt($input_file, $output_file);
            $vtt2srt->run();
        }
        catch (Exception $exc) {
            $message = $exc->getMessage();
            echo "Error: {$message}.", PHP_EOL;
            exit(1);
        }

        exit(0);
    }

}

Main::run();
