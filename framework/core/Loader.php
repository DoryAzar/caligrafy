<?php

/**
 * Loader.php
 * Framework loader
 * @author Dory A.Azar
 * @copyright 2019
 * @version 1.0
 *
*/

/**
 * Class Loader loads any additional librairies and helpers needed per module
 * Framework loader loads any additional librairies and helpers needed per module
 * @author Dory A
*/

class Loader{

    // Load library classes
    public function library($lib){
        include LIB_PATH . "$lib.php";
    }

    // loader helper functions.
    public function helper($helper){
        include HELPER_PATH . "$helper.php";
    }

}