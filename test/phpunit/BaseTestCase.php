<?php

/**
 * Description of BaseTestCase
 *
 * @author nacho
 */
class BaseTestCase extends PHPUnit_Extensions_SeleniumTestCase
{

    protected function setUp()
    {
        $this->setBrowser("*chrome");
        $this->setBrowserUrl("http://beta.site/administrator/index.php?option=com_quipu");
    }

}
