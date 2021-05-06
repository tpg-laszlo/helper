<?php

    declare(strict_types = 1);

    namespace TpgHelper;

    class TpgHelper
    {
        public function __construct()
        {
        }

        /**
         * Formatted output
         */
        public static function e( $string = '', $stop = 1, $strDesc = null, $boolDump = false )
        {
            // if (empty($string)) { exit; }

            echo '
                <style>
        
                    .pre { 
                        font-weight:      bold;
                        color:            #56DB3A;
                        text-align:       left;
                        font-family:      courier;
                        background-color: black;
                        padding:          10px;
                        margin:           10px;
                        border:           3px ridge #980000;
                        position:         relative;
                        z-index:          999;
                    }
                    .cfb {
                        color:  cornflowerblue;
                    }
                    .red {
                        color:  red;
                    }
                    .h2{
                        cursor:             pointer;
                        border:             1px solid grey;
                        background-color:   #FFC2C2;
                        width:              250px;
                        margin-top:         5px;
                        display:            table;
                    }
        
                    .classdiv {
                        position:   relative;
                        top:        -40px;
                        
                    }
                    .ml20 {
                        margin-left:    20px;
                    }
                
                </style>';

            $b = debug_backtrace();
            echo '<pre class="pre">';

            if (!isset($b[1]['file'])) {
                $b[1]['file'] = null;
                $b[1]['line'] = null;
            }

            echo 'Aufruf:' . "\n\n" .
                        "&nbsp;Datei:<span class='cfb'>" . $b[0]['file'] . "</span>\n" .
                        '&nbsp;Zeile: ' . $b[0]['line'] . "\n\n" .
                        '&nbsp;Backtrace:' . "\n" .
                        "&nbsp;Datei:<span class='cfb'>" . $b[1]['file'] . "</span>\n" .
                        '&nbsp;Zeile: ' . $b[1]['line'] . "\n\n" .
                        '';

            if (null !== $strDesc || '' !== $strDesc) {
                echo '<b>' . $strDesc . '</b><br />';
            }

            $strVarType = gettype($string);

            if ($string) {
                echo "<div class='cfb'>Vartype:" . $strVarType . '<br /><br /></div>';
            }

            if (is_array($string)) {
                echo "<div class='red'>Arraysize: " . count($string) . '<br /></div>';
            }

            echo 'Inhalt: ';

            if ('UserClass' === $boolDump) {
                foreach (get_declared_classes() as $val) {
                    $reflect = new \ReflectionClass($val);

                    if ($reflect->isInternal()) {
                    } else {
                        echo "<h2 class='h2'>Class " . $val . "()</h2>
                            
                            <div class='classdiv'>";

                        foreach ($reflect->getMethods() as $reflectmethod) {
                            echo "<p class='ml20'>" . $reflectmethod->getName() . '()</p>';
                        }
                        echo '</div>';
                    }
                }
            } elseif ($boolDump) {
                var_dump($string);
            } else {
                if ('boolean' === $strVarType) {
                    if (true === $string) {
                        echo 'true';
                    } else {
                        echo 'false';
                    }
                } else {
                    print_r($string);
                }
            }
            echo '</pre>';

            if (1 == $stop) { exit; }
        }

        /**
         * Output declared classes
         */
        public static function getClasses( $stop = 1 )
        {

            self::e( get_declared_classes(), $stop );
        }

        /**
         * Output all methods a declared class
         */
        public static function getClassMethod( $class, $stop = 1 )
        {
            self::e( get_class_methods( $class ), $stop );
        }

        /**
         * Output class name
         */
        public static function getClassName( $class, $stop = 1 )
        {

            self::e( get_class( $class ) );
        }

        public static function reflectionClassisUserDefined($className)
        {
            $class = new \ReflectionClass($className);
            self::e($class->isUserDefined());
        }

        public static function getSelfMethodes()
        {
            self::getClassMethod('Helper');
        }

        public static function arrayObject()
        {
            self::e('
            https://www.php.net/manual/de/class.arrayobject.php

            $object = new ArrayObject();

            $object["dashier"]  = 5;
            $object["dashier2"] = 5;
            $object["dashier3"] = 5;

            $object->offsetSet("dashier4",6);

            $object->count();

            $object->serialize();

            $array = (array) $object;

            $arrayobj = new ArrayObject(array("first","second","third"));
            $arrayobj->append("fourth");
            $arrayobj->append(array("five", "six"));
            var_dump($arrayobj);

            // Array of available fruits
            $fruits = array("lemons" => 1, "oranges" => 4, "bananas" => 5, "apples" => 10);
            
            $fruitsArrayObject = new ArrayObject($fruits);
            
            // Get the current flags
            $flags = $fruitsArrayObject->getFlags();
            var_dump($flags);
            
            // Set new flags
            $fruitsArrayObject->setFlags(ArrayObject::ARRAY_AS_PROPS);
            
            // Get the new flags
            $flags = $fruitsArrayObject->getFlags();
            ');
        }
    }