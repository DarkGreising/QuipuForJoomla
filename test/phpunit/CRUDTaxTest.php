<?php
class Example extends BaseTestCase
{

  public function testMyTestCase()
  {
    $this->open("http://beta.site/administrator/index.php?option=com_quipu&view=config&layout=edit");
    $this->click("link=Quipu ERP");
    $this->waitForPageToLoad("30000");
    $this->verifyTextPresent("There is no configuration registered. You have to create one to start using Quipu");
    try {
        $this->assertTrue($this->isElementPresent("//a[contains(@href, 'index.php?option=com_quipu')]"));
    } catch (PHPUnit_Framework_AssertionFailedError $e) {
        array_push($this->verificationErrors, $e->toString());
    }
    try {
        $this->assertTrue($this->isElementPresent("//a[contains(@href, 'index.php?option=com_quipu&view=customers')]"));
    } catch (PHPUnit_Framework_AssertionFailedError $e) {
        array_push($this->verificationErrors, $e->toString());
    }
    try {
        $this->assertTrue($this->isElementPresent("//a[contains(@href, 'index.php?option=com_quipu&view=suppliers')]"));
    } catch (PHPUnit_Framework_AssertionFailedError $e) {
        array_push($this->verificationErrors, $e->toString());
    }
    try {
        $this->assertTrue($this->isElementPresent("//a[contains(@href, 'index.php?option=com_quipu&view=itemcategories')]"));
    } catch (PHPUnit_Framework_AssertionFailedError $e) {
        array_push($this->verificationErrors, $e->toString());
    }
    try {
        $this->assertTrue($this->isElementPresent("//a[contains(@href, 'index.php?option=com_quipu&view=items')]"));
    } catch (PHPUnit_Framework_AssertionFailedError $e) {
        array_push($this->verificationErrors, $e->toString());
    }
    try {
        $this->assertTrue($this->isElementPresent("//a[contains(@href, 'index.php?option=com_quipu&view=purchaseorders')]"));
    } catch (PHPUnit_Framework_AssertionFailedError $e) {
        array_push($this->verificationErrors, $e->toString());
    }
    try {
        $this->assertTrue($this->isElementPresent("//a[contains(@href, 'index.php?option=com_quipu&view=orders')]"));
    } catch (PHPUnit_Framework_AssertionFailedError $e) {
        array_push($this->verificationErrors, $e->toString());
    }
    try {
        $this->assertTrue($this->isElementPresent("//a[contains(@href, 'index.php?option=com_quipu&view=invoices')]"));
    } catch (PHPUnit_Framework_AssertionFailedError $e) {
        array_push($this->verificationErrors, $e->toString());
    }
    try {
        $this->assertTrue($this->isElementPresent("//a[contains(@href, 'index.php?option=com_quipu&view=bankaccounts')]"));
    } catch (PHPUnit_Framework_AssertionFailedError $e) {
        array_push($this->verificationErrors, $e->toString());
    }
    try {
        $this->assertTrue($this->isElementPresent("//a[contains(@href, 'index.php?option=com_quipu&view=bankactivities')]"));
    } catch (PHPUnit_Framework_AssertionFailedError $e) {
        array_push($this->verificationErrors, $e->toString());
    }
    try {
        $this->assertTrue($this->isElementPresent("//a[contains(@href, 'index.php?option=com_quipu&view=taxes')]"));
    } catch (PHPUnit_Framework_AssertionFailedError $e) {
        array_push($this->verificationErrors, $e->toString());
    }
    try {
        $this->assertTrue($this->isElementPresent("//a[contains(@href, 'index.php?option=com_quipu&task=config.edit&id=')]"));
    } catch (PHPUnit_Framework_AssertionFailedError $e) {
        array_push($this->verificationErrors, $e->toString());
    }
    try {
        $this->assertTrue($this->isElementPresent("css=span.icon-32-save"));
    } catch (PHPUnit_Framework_AssertionFailedError $e) {
        array_push($this->verificationErrors, $e->toString());
    }
    try {
        $this->assertTrue($this->isElementPresent("css=span.icon-32-cancel"));
    } catch (PHPUnit_Framework_AssertionFailedError $e) {
        array_push($this->verificationErrors, $e->toString());
    }
    $this->type("id=jform_company_name", "SELENIUM");
    $this->type("id=jform_vatno", "VATSELENIUM");
    $this->type("id=jform_address", "ADDRESSSELENIUM");
    $this->type("id=jform_zip", "ZIPSELENIUM");
    $this->type("id=jform_state", "STATESELENIUM");
    $this->type("id=jform_phone", "TELEPHONESELENIUM");
    $this->type("id=jform_email", "EMAILSELENIUM");
    $this->type("id=jform_web", "WEBSELENIUM");
    $this->type("id=jform_seq_invoice", "10001");
    $this->click("id=jform_seq_refund");
    $this->type("id=jform_seq_refund", "10001");
    $this->type("id=jform_seq_purchaseorder", "10001");
    $this->type("id=jform_seq_order", "10001");
    $this->click("css=span.icon-32-save");
    $this->waitForPageToLoad("30000");
    $this->verifyTextPresent("Item successfully saved.");
    $this->verifyTextPresent("Invoicing");
    $this->verifyTextPresent("Pending invoices");
    $this->verifyTextPresent("Orders by state");
    $this->verifyTextPresent("Recently registered customers");
    try {
        $this->assertEquals("10001", $this->getValue("id=jform_seq_invoice"));
    } catch (PHPUnit_Framework_AssertionFailedError $e) {
        array_push($this->verificationErrors, $e->toString());
    }
    try {
        $this->assertEquals("10001", $this->getValue("id=jform_seq_refund"));
    } catch (PHPUnit_Framework_AssertionFailedError $e) {
        array_push($this->verificationErrors, $e->toString());
    }
    try {
        $this->assertEquals("10001", $this->getValue("id=jform_seq_order"));
    } catch (PHPUnit_Framework_AssertionFailedError $e) {
        array_push($this->verificationErrors, $e->toString());
    }
    try {
        $this->assertEquals("10001", $this->getValue("id=jform_seq_purchaseorder"));
    } catch (PHPUnit_Framework_AssertionFailedError $e) {
        array_push($this->verificationErrors, $e->toString());
    }
    $this->verifyTextPresent("Pending orders");
    $this->click("link=Configuration");
    $this->waitForPageToLoad("30000");
    try {
        $this->assertTrue($this->isElementPresent("id=jform_currency_symbol"));
    } catch (PHPUnit_Framework_AssertionFailedError $e) {
        array_push($this->verificationErrors, $e->toString());
    }
    $this->type("id=jform_currency_symbol", "€");
    $this->click("link=Save & Close");
    $this->waitForPageToLoad("30000");
    $this->verifyTextPresent("Item successfully saved.");
    $this->click("link=Configuration");
    $this->waitForPageToLoad("30000");
    try {
        $this->assertEquals("€", $this->getValue("id=jform_currency_symbol"));
    } catch (PHPUnit_Framework_AssertionFailedError $e) {
        array_push($this->verificationErrors, $e->toString());
    }
    $this->click("link=Close");
    $this->waitForPageToLoad("30000");
  }
}
?>