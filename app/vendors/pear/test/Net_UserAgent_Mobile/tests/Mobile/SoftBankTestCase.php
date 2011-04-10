<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */

/**
 * PHP versions 5
 *
 * Copyright (c) 2008-2009 KUBO Atsuhiro <kubo@iteman.jp>,
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *
 *     * Redistributions of source code must retain the above copyright
 *       notice, this list of conditions and the following disclaimer.
 *     * Redistributions in binary form must reproduce the above copyright
 *       notice, this list of conditions and the following disclaimer in the
 *       documentation and/or other materials provided with the distribution.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
 * ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE
 * LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR
 * CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF
 * SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
 * INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
 * CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
 * ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * @category   Networking
 * @package    Net_UserAgent_Mobile
 * @author     KUBO Atsuhiro <kubo@iteman.jp>
 * @copyright  2008-2009 KUBO Atsuhiro <kubo@iteman.jp>
 * @license    http://www.opensource.org/licenses/bsd-license.php  New BSD License
 * @version    CVS: $Id: SoftBankTestCase.php,v 1.11 2009/05/10 17:28:48 kuboa Exp $
 * @since      File available since Release 0.31.0
 */

require_once dirname(__FILE__) . '/AbstractTestCase.php';
require_once 'Net/UserAgent/Mobile/SoftBank.php';

// {{{ Net_UserAgent_Mobile_SoftBankTestCase

/**
 * Some tests for Net_UserAgent_Mobile_SoftBank.
 *
 * @category   Networking
 * @package    Net_UserAgent_Mobile
 * @author     KUBO Atsuhiro <kubo@iteman.jp>
 * @copyright  2008-2009 KUBO Atsuhiro <kubo@iteman.jp>
 * @license    http://www.opensource.org/licenses/bsd-license.php  New BSD License
 * @version    Release: 1.0.0
 * @since      Class available since Release 0.31.0
 */
class Net_UserAgent_Mobile_SoftBankTestCase extends Net_UserAgent_Mobile_AbstractTestCase
{

    // {{{ properties

    /**#@+
     * @access public
     */

    /**#@-*/

    /**#@+
     * @access protected
     */

    /**#@-*/

    /**#@+
     * @access private
     */

    private $_profiles = array(

                               // 3GC
                               'SoftBank/1.0/810P/PJP10/SN123456789012345 Browser/NetFront/3.4 Profile/MIDP-2.0 Configuration/CLDC-1.1' => array('model' => '810P'),
                               'SoftBank/1.0/805SC/SCJ001/SN123456789012345 Browser/NetFront/3.3 Profile/MIDP-2.0 Configuration/CLDC-1.1' => array('model' => '805SC'),
                               'SoftBank/1.0/912SH/SHJ001/SN123456789012345 Browser/NetFront/3.4 Profile/MIDP-2.0 Configuration/CLDC-1.1' => array('model' => '912SH'),
                               'SoftBank/1.0/813SH/SHJ001/SN123456789012345 Browser/NetFront/3.3 Profile/MIDP-2.0 Configuration/CLDC-1.1' => array('model' => '813SH'),
                               'SoftBank/1.0/707SC2/SCJ001/SN123456789012345 Browser/NetFront/3.3 Profile/MIDP-2.0 Configuration/CLDC-1.1' => array('model' => '707SC2'),
                               'SoftBank/1.0/911T/TJ001/SN123456789012345 Browser/NetFront/3.3 Profile/MIDP-2.0 Configuration/CLDC-1.1' => array('model' => '911T'),
                               'SoftBank/1.0/813SHe/SHJ001/SN123456789012345 Browser/NetFront/3.3 Profile/MIDP-2.0 Configuration/CLDC-1.1' => array('model' => '813SHe'),
                               'SoftBank/1.0/813T/TJ001/SN123456789012345 Browser/NetFront/3.3 Profile/MIDP-2.0 Configuration/CLDC-1.1' => array('model' => '813T'),
                               'SoftBank/1.0/708SC/SCJ001/SN123456789012345 Browser/NetFront/3.3' => array('model' => '708SC'),
                               'SoftBank/1.0/706N/NJ001/SN123456789012345 Browser/NetFront/3.3 Profile/MIDP-2.0 Configuration/CLDC-1.1' => array('model' => '706N'),
                               'SoftBank/1.0/706P/PJP10/SN123456789012345 Browser/Teleca-Browser/3.1 Profile/MIDP-2.0 Configuration/CLDC-1.1' => array('model' => '706P'),
                               'SoftBank/1.0/812T/TJ001/SN123456789012345 Browser/NetFront/3.3 Profile/MIDP-2.0 Configuration/CLDC-1.1' => array('model' => '812T'),
                               'SoftBank/1.0/812SH/SHJ001/SN123456789012345 Browser/NetFront/3.3 Profile/MIDP-2.0 Configuration/CLDC-1.1' => array('model' => '812SH'),
                               'SoftBank/1.0/709SC/SCJ001/SN123456789012345 Browser/NetFront/3.3 Profile/MIDP-2.0 Configuration/CLDC-1.1' => array('model' => '709SC'),
                               'SoftBank/1.0/707SC/SCJ001/SN123456789012345 Browser/NetFront/3.3 Profile/MIDP-2.0 Configuration/CLDC-1.1' => array('model' => '707SC'),
                               'SoftBank/1.0/911SH/SHJ001/SN123456789012345 Browser/NetFront/3.3 Profile/MIDP-2.0 Configuration/CLDC-1.1' => array('model' => '911SH'),
                               'SoftBank/1.0/910SH/SHJ001/SN123456789012345 Browser/NetFront/3.3 Profile/MIDP-2.0 Configuration/CLDC-1.1' => array('model' => '910SH'),
                               'SoftBank/1.0/910T/TJ001/SN123456789012345 Browser/NetFront/3.3 Profile/MIDP-2.0 Configuration/CLDC-1.1' => array('model' => '910T'),
                               'SoftBank/1.0/810SH/SHJ001/SN123456789012345 Browser/NetFront/3.3 Profile/MIDP-2.0 Configuration/CLDC-1.1' => array('model' => '810SH'),
                               'SoftBank/1.0/811SH/SHJ001/SN123456789012345 Browser/NetFront/3.3 Profile/MIDP-2.0 Configuration/CLDC-1.1' => array('model' => '811SH'),
                               'SoftBank/1.0/810T/TJ001/SN123456789012345 Browser/NetFront/3.3 Profile/MIDP-2.0 Configuration/CLDC-1.1' => array('model' => '810T'),
                               'SoftBank/1.0/811T/TJ001/SN123456789012345 Browser/NetFront/3.3 Profile/MIDP-2.0 Configuration/CLDC-1.1' => array('model' => '811T'),
                               'SoftBank/1.0/705N/NJ001/SN123456789012345 Browser/NetFront/3.3 Profile/MIDP-2.0 Configuration/CLDC-1.1' => array('model' => '705N'),
                               'SoftBank/1.0/705NK/NKJ001/SN123456789012345 Series60/3.0 NokiaN73/3.0650 Profile/MIDP-2.0 Configuration/CLDC-1.1' => array('model' => '705NK'),
                               'SoftBank/1.0/705P/PJP10/SN123456789012345 Browser/Teleca-Browser/3.1 Profile/MIDP-2.0 Configuration/CLDC-1.1' => array('model' => '705P'),
                               'SoftBank/1.0/705SC/SCJ001/SN123456789012345 Browser/NetFront/3.3 Profile/MIDP-2.0 Configuration/CLDC-1.1' => array('model' => '705SC'),
                               'SoftBank/1.0/706SC/SCJ001/SN123456789012345 Browser/NetFront/3.3 Profile/MIDP-2.0 Configuration/CLDC-1.1' => array('model' => '706SC'),
                               'SoftBank/1.0/810P/PJP10 Browser/NetFront/3.4 Profile/MIDP-2.0 Configuration/CLDC-1.1' => array('model' => '810P'),
                               'SoftBank/1.0/805SC/SCJ001 Browser/NetFront/3.3 Profile/MIDP-2.0 Configuration/CLDC-1.1' => array('model' => '805SC'),
                               'SoftBank/1.0/912SH/SHJ001 Browser/NetFront/3.4 Profile/MIDP-2.0 Configuration/CLDC-1.1' => array('model' => '912SH'),
                               'SoftBank/1.0/813SH/SHJ001 Browser/NetFront/3.3 Profile/MIDP-2.0 Configuration/CLDC-1.1' => array('model' => '813SH'),
                               'SoftBank/1.0/707SC2/SCJ001 Browser/NetFront/3.3 Profile/MIDP-2.0 Configuration/CLDC-1.1' => array('model' => '707SC2'),
                               'SoftBank/1.0/911T/TJ001 Browser/NetFront/3.3 Profile/MIDP-2.0 Configuration/CLDC-1.1' => array('model' => '911T'),
                               'SoftBank/1.0/813SHe/SHJ001 Browser/NetFront/3.3 Profile/MIDP-2.0 Configuration/CLDC-1.1' => array('model' => '813SHe'),
                               'SoftBank/1.0/813T/TJ001 Browser/NetFront/3.3 Profile/MIDP-2.0 Configuration/CLDC-1.1' => array('model' => '813T'),
                               'SoftBank/1.0/708SC/SCJ001 Browser/NetFront/3.3' => array('model' => '708SC'),
                               'SoftBank/1.0/706N/NJ001 Browser/NetFront/3.3 Profile/MIDP-2.0 Configuration/CLDC-1.1' => array('model' => '706N'),
                               'SoftBank/1.0/706P/PJP10 Browser/Teleca-Browser/3.1 Profile/MIDP-2.0 Configuration/CLDC-1.1' => array('model' => '706P'),
                               'SoftBank/1.0/812T/TJ001 Browser/NetFront/3.3 Profile/MIDP-2.0 Configuration/CLDC-1.1' => array('model' => '812T'),
                               'SoftBank/1.0/812SH/SHJ001 Browser/NetFront/3.3 Profile/MIDP-2.0 Configuration/CLDC-1.1' => array('model' => '812SH'),
                               'SoftBank/1.0/709SC/SCJ001 Browser/NetFront/3.3 Profile/MIDP-2.0 Configuration/CLDC-1.1' => array('model' => '709SC'),
                               'SoftBank/1.0/707SC/SCJ001 Browser/NetFront/3.3 Profile/MIDP-2.0 Configuration/CLDC-1.1' => array('model' => '707SC'),
                               'SoftBank/1.0/911SH/SHJ001 Browser/NetFront/3.3 Profile/MIDP-2.0 Configuration/CLDC-1.1' => array('model' => '911SH'),
                               'SoftBank/1.0/910SH/SHJ001 Browser/NetFront/3.3 Profile/MIDP-2.0 Configuration/CLDC-1.1' => array('model' => '910SH'),
                               'SoftBank/1.0/910T/TJ001 Browser/NetFront/3.3 Profile/MIDP-2.0 Configuration/CLDC-1.1' => array('model' => '910T'),
                               'SoftBank/1.0/810SH/SHJ001 Browser/NetFront/3.3 Profile/MIDP-2.0 Configuration/CLDC-1.1' => array('model' => '810SH'),
                               'SoftBank/1.0/811SH/SHJ001 Browser/NetFront/3.3 Profile/MIDP-2.0 Configuration/CLDC-1.1' => array('model' => '811SH'),
                               'SoftBank/1.0/810T/TJ001 Browser/NetFront/3.3 Profile/MIDP-2.0 Configuration/CLDC-1.1' => array('model' => '810T'),
                               'SoftBank/1.0/811T/TJ001 Browser/NetFront/3.3 Profile/MIDP-2.0 Configuration/CLDC-1.1' => array('model' => '811T'),
                               'SoftBank/1.0/705N/NJ001 Browser/NetFront/3.3 Profile/MIDP-2.0 Configuration/CLDC-1.1' => array('model' => '705N'),
                               'SoftBank/1.0/705NK/NKJ001 Series60/3.0 NokiaN73/3.0650 Profile/MIDP-2.0 Configuration/CLDC-1.1' => array('model' => '705NK'),
                               'SoftBank/1.0/705P/PJP10 Browser/Teleca-Browser/3.1 Profile/MIDP-2.0 Configuration/CLDC-1.1' => array('model' => '705P'),
                               'SoftBank/1.0/705SC/SCJ001 Browser/NetFront/3.3 Profile/MIDP-2.0 Configuration/CLDC-1.1' => array('model' => '705SC'),
                               'SoftBank/1.0/706SC/SCJ001 Browser/NetFront/3.3 Profile/MIDP-2.0 Configuration/CLDC-1.1' => array('model' => '706SC')
                               );

    /**#@-*/

    /**#@+
     * @access public
     */

    public function testShouldDetectUserAgentsAsSoftbank()
    {
        reset($this->_profiles);
        while (list($userAgent, $profile) = each($this->_profiles)) {
            $agent = new Net_UserAgent_Mobile_SoftBank($userAgent);

            $this->assertFalse($agent->isDoCoMo());
            $this->assertFalse($agent->isEZweb());
            $this->assertTrue($agent->isSoftBank());
            $this->assertFalse($agent->isWillcom());
            $this->assertFalse($agent->isNonMobile());

            $this->assertTrue($agent->isJPhone());
            $this->assertTrue($agent->isVodafone());
        }
    }

    public function testShouldProvideTheModelNameOfAUserAgent()
    {
        reset($this->_profiles);
        while (list($userAgent, $profile) = each($this->_profiles)) {
            $agent = new Net_UserAgent_Mobile_SoftBank($userAgent);

            $this->assertEquals($profile['model'], $agent->getModel());
        }
    }

    public function testShouldSupportSemulator()
    {
        $agent = new Net_UserAgent_Mobile_SoftBank('Semulator/1.0/813T/TJ001/SN123456789012345 Browser/NetFront/3.3 Profile/MIDP-2.0 Configuration/CLDC-1.1');

        $this->assertTrue($agent->isSoftBank());
        $this->assertEquals('813T', $agent->getModel());
        $this->assertTrue($agent->isPacketCompliant());
        $this->assertEquals('123456789012345', $agent->getSerialNumber());
        $this->assertEquals('J001', $agent->getVendorVersion());
        $this->assertEquals(array('Profile' => 'MIDP-2.0',
                                  'Configuration' => 'CLDC-1.1'),
                            $agent->getJavaInfo()
                            );
        $this->assertTrue($agent->isType3GC());
        $this->assertEquals('Semulator', $agent->getName());
        $this->assertEquals('1.0', $agent->getVersion());
        $this->assertEquals('T', $agent->getVendor());

        $agent = new Net_UserAgent_Mobile_SoftBank('Vemulator/1.0/V902SH/SHJ001/SN123456789012345');

        $this->assertTrue($agent->isSoftBank());
        $this->assertEquals('V902SH', $agent->getModel());
        $this->assertTrue($agent->isPacketCompliant());
        $this->assertEquals('123456789012345', $agent->getSerialNumber());
        $this->assertEquals('J001', $agent->getVendorVersion());
        $this->assertTrue($agent->isType3GC());
        $this->assertEquals('Vemulator', $agent->getName());
        $this->assertEquals('1.0', $agent->getVersion());
        $this->assertEquals('SH', $agent->getVendor());
    }

    public function testShouldSupportVemulator()
    {
        $agent = new Net_UserAgent_Mobile_SoftBank('Vemulator/1.0/V902SH/SHJ001/SN123456789012345');

        $this->assertTrue($agent->isSoftBank());
        $this->assertEquals('V902SH', $agent->getModel());
        $this->assertTrue($agent->isPacketCompliant());
        $this->assertEquals('123456789012345', $agent->getSerialNumber());
        $this->assertEquals('J001', $agent->getVendorVersion());
        $this->assertTrue($agent->isType3GC());
        $this->assertEquals('Vemulator', $agent->getName());
        $this->assertEquals('1.0', $agent->getVersion());
        $this->assertEquals('SH', $agent->getVendor());
    }

    public function testShouldSupportJemulator()
    {
        $agent = new Net_UserAgent_Mobile_SoftBank('J-EMULATOR/4.3/V602SH/SN12345678901');

        $this->assertTrue($agent->isSoftBank());
        $this->assertEquals('V602SH', $agent->getModel());
        $this->assertFalse($agent->isPacketCompliant());
        $this->assertEquals('12345678901', $agent->getSerialNumber());
        $this->assertTrue($agent->isTypeP());
        $this->assertEquals('J-EMULATOR', $agent->getName());
        $this->assertEquals('4.3', $agent->getVersion());
        $this->assertEquals('SH', $agent->getVendor());
    }

    /**
     * @since Method available since Release 1.0.0RC1
     */
    public function testShouldProvideTheUidOfASubscriber()
    {
        $uid = '1234567890123456';
        $_SERVER['HTTP_X_JPHONE_UID'] = $uid;
        $agent = new Net_UserAgent_Mobile_SoftBank('SoftBank/1.0/706SC/SCJ001 Browser/NetFront/3.3 Profile/MIDP-2.0 Configuration/CLDC-1.1');

        $this->assertEquals($uid, $agent->getUID());

        unset($_SERVER['HTTP_X_JPHONE_UID']);
        $agent = new Net_UserAgent_Mobile_SoftBank('SoftBank/1.0/706SC/SCJ001 Browser/NetFront/3.3 Profile/MIDP-2.0 Configuration/CLDC-1.1');

        $this->assertNull($agent->getUID());
    }

    /**
     * @since Method available since Release 1.0.0RC1
     */
    public function testShouldProvideTheJavaInformationOfAUserAgent()
    {
        $profiles = array('J-PHONE/4.0/J-SH51/SNJSHA3029293 SH/0001aa Profile/MIDP-1.0 Configuration/CLDC-1.0 Ext-Profile/JSCL-1.1.0' => array('Profile' => 'MIDP-1.0', 'Configuration' => 'CLDC-1.0', 'Ext-Profile' => 'JSCL-1.1.0'),
                          'Vodafone/1.0/V702NK/NKJ001 Series60/2.6 Nokia6630/2.39.148 Profile/MIDP-2.0 Configuration/CLDC-1.1' => array('Profile' => 'MIDP-2.0', 'Configuration' => 'CLDC-1.1'),
                          'Vodafone/1.0/V802SE/SEJ001/SN123456789012345 Browser/SEMC-Browser/4.1 Profile/MIDP-2.0 Configuration/CLDC-1.1' => array('model' => 'V802SE', 'Profile' => 'MIDP-2.0', 'Configuration' => 'CLDC-1.1'),
                          'Vodafone/1.0/V902SH/SHJ001 Browser/UP.Browser/7.0.2.1 Profile/MIDP-2.0 Configuration/CLDC-1.1 Ext-J-Profile/JSCL-1.2.2 Ext-V-Profile/VSCL-2.0.0' => array('model' => 'V902SH', 'Profile' => 'MIDP-2.0', 'Configuration' => 'CLDC-1.1'),
                          'Vodafone/1.0/V802N/NJ001 Browser/UP.Browser/7.0.2.1.258 Profile/MIDP-2.0 Configuration/CLDC-1.1 Ext-J-Profile/JSCL-1.2.2 Ext-V-Profile/VSCL-2.0.0' => array('model' => 'V802N', 'Profile' => 'MIDP-2.0', 'Configuration' => 'CLDC-1.1'),
                          'MOT-V980/80.2F.2E. MIB/2.2.1 Profile/MIDP-2.0 Configuration/CLDC-1.1' => array('model' => 'V980', 'Profile' => 'MIDP-2.0', 'Configuration' => 'CLDC-1.1'),
                          'SoftBank/1.0/705P/PJP10 Browser/Teleca-Browser/3.1 Profile/MIDP-2.0 Configuration/CLDC-1.1' => array('model' => '705P', 'Profile' => 'MIDP-2.0', 'Configuration' => 'CLDC-1.1')
                          );
        while (list($userAgent, $profile) = each($profiles)) {
            $agent = new Net_UserAgent_Mobile_SoftBank($userAgent);
            $javaInfo = $agent->getJavaInfo();

            $this->assertEquals($profile['Profile'], $javaInfo['Profile']);
            $this->assertEquals($profile['Configuration'], $javaInfo['Configuration']);

            if (array_key_exists('Ext-Profile', $profile)) {
                $this->assertEquals($profile['Ext-Profile'], $javaInfo['Ext-Profile']);
            } else {
                if (array_key_exists('Ext-Profile', $javaInfo)) {
                    $this->fail($agent->getModel());
                }
            }
        }
    }

    /**
     * @since Method available since Release 1.0.0RC1
     */
    public function testShouldTellWhetherAUserAgentIsAModelTypeC()
    {
        foreach (array('J-PHONE/2.0/J-DN02', 'J-PHONE/3.0/J-PE03_a') as $userAgent) {
            $agent = new Net_UserAgent_Mobile_SoftBank($userAgent);

            $this->assertTrue($agent->isTypeC());
            $this->assertFalse($agent->isTypeP());
            $this->assertFalse($agent->isTypeW());
            $this->assertFalse($agent->isType3GC());
        }
    }

    /**
     * @since Method available since Release 1.0.0RC1
     */
    public function testShouldTellWhetherAUserAgentIsAModelTypeP()
    {
        $agent = new Net_UserAgent_Mobile_SoftBank('J-PHONE/4.0/J-SH51/SNJSHA3029293 SH/0001aa Profile/MIDP-1.0 Configuration/CLDC-1.0 Ext-Profile/JSCL-1.1.0');

        $this->assertFalse($agent->isTypeC());
        $this->assertTrue($agent->isTypeP());
        $this->assertFalse($agent->isTypeW());
        $this->assertFalse($agent->isType3GC());
    }

    /**
     * @since Method available since Release 1.0.0RC1
     */
    public function testShouldTellWhetherAUserAgentIsAModelTypeW()
    {
        $agent = new Net_UserAgent_Mobile_SoftBank('J-PHONE/5.0/V801SA');

        $this->assertFalse($agent->isTypeC());
        $this->assertFalse($agent->isTypeP());
        $this->assertTrue($agent->isTypeW());
        $this->assertFalse($agent->isType3GC());
    }

    /**
     * @since Method available since Release 1.0.0RC1
     */
    public function testShouldTellWhetherAUserAgentIsAModelType3Gc()
    {
        $agent = new Net_UserAgent_Mobile_SoftBank('Vodafone/1.0/V702NK/NKJ001 Series60/2.6 Nokia6630/2.39.148 Profile/MIDP-2.0 Configuration/CLDC-1.1');

        $this->assertFalse($agent->isTypeC());
        $this->assertFalse($agent->isTypeP());
        $this->assertFalse($agent->isTypeW());
        $this->assertTrue($agent->isType3GC());
    }

    /**
     * @since Method available since Release 1.0.0RC1
     */
    public function testShouldProvideTheMsnameOfAUserAgent()
    {
        $_SERVER['HTTP_X_JPHONE_MSNAME'] = 'V702NK';
        $agent = new Net_UserAgent_Mobile_SoftBank('Vodafone/1.0/V702NK/NKJ001 Series60/2.6 Nokia6630/2.39.148 Profile/MIDP-2.0 Configuration/CLDC-1.1');

        $this->assertEquals('V702NK', $agent->getMsname());

        unset($_SERVER['HTTP_X_JPHONE_MSNAME']);
    }

    /**
     * @since Method available since Release 1.0.0RC1
     */
    public function testShouldProvideTheScreenInformationOfAUserAgent()
    {
        $_SERVER['HTTP_X_JPHONE_DISPLAY'] = '120*117';
        $_SERVER['HTTP_X_JPHONE_COLOR'] = 'C256';
        $agent = new Net_UserAgent_Mobile_SoftBank('J-PHONE/2.0/J-DN02');

        $display = $agent->getDisplay();

        $this->assertEquals(120, $display->getWidth());
        $this->assertEquals(117, $display->getHeight());
        $this->assertTrue($display->isColor());
        $this->assertEquals(256, $display->getDepth());

        unset($_SERVER['HTTP_X_JPHONE_COLOR']);
        unset($_SERVER['HTTP_X_JPHONE_DISPLAY']);
    }

    /**#@-*/

    /**#@+
     * @access protected
     */

    /**#@-*/

    /**#@+
     * @access private
     */

    /**#@-*/

    // }}}
}

// }}}

/*
 * Local Variables:
 * mode: php
 * coding: iso-8859-1
 * tab-width: 4
 * c-basic-offset: 4
 * c-hanging-comment-ender-p: nil
 * indent-tabs-mode: nil
 * End:
 */
