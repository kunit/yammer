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
 * @version    CVS: $Id: EZwebTestCase.php,v 1.8 2009/05/10 17:28:48 kuboa Exp $
 * @since      File available since Release 0.31.0
 */

require_once dirname(__FILE__) . '/AbstractTestCase.php';
require_once 'Net/UserAgent/Mobile/EZweb.php';

// {{{ Net_UserAgent_Mobile_EZwebTestCase

/**
 * Some tests for Net_UserAgent_Mobile_EZweb.
 *
 * @category   Networking
 * @package    Net_UserAgent_Mobile
 * @author     KUBO Atsuhiro <kubo@iteman.jp>
 * @copyright  2008-2009 KUBO Atsuhiro <kubo@iteman.jp>
 * @license    http://www.opensource.org/licenses/bsd-license.php  New BSD License
 * @version    Release: 1.0.0
 * @since      Class available since Release 0.31.0
 */
class Net_UserAgent_Mobile_EZwebTestCase extends Net_UserAgent_Mobile_AbstractTestCase
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

    private $_profiles = array('KDDI-CA23 UP.Browser/6.2.0.3.111 (GUI) MMP/2.0' => array('model' => 'CA23'),
                               'KDDI-CA24 UP.Browser/6.2.0.5 (GUI) MMP/2.0' => array('model' => 'CA24'),
                               'KDDI-CA25 UP.Browser/6.2.0.6.2 (GUI) MMP/2.0' => array('model' => 'CA25'),
                               'KDDI-CA26 UP.Browser/6.2.0.6.2 (GUI) MMP/2.0' => array('model' => 'CA26'),
                               'KDDI-CA27 UP.Browser/6.2.0.9 (GUI) MMP/2.0' => array('model' => 'CA27'),
                               'KDDI-CA28 UP.Browser/6.2.0.9 (GUI) MMP/2.0' => array('model' => 'CA28'),
                               'KDDI-CA31 UP.Browser/6.2.0.7.3.129 (GUI) MMP/2.0' => array('model' => 'CA31'),
                               'KDDI-CA32 UP.Browser/6.2.0.7.3.129 (GUI) MMP/2.0' => array('model' => 'CA32'),
                               'KDDI-CA33 UP.Browser/6.2.0.10.4 (GUI) MMP/2.0' => array('model' => 'CA33'),
                               'KDDI-CA34 UP.Browser/6.2.0.10.3.3 (GUI) MMP/2.0' => array('model' => 'CA34'),
                               'KDDI-CA35 UP.Browser/6.2.0.11.1.2 (GUI) MMP/2.0' => array('model' => 'CA35'),
                               'KDDI-CA37 UP.Browser/6.2.0.12.1.3 (GUI) MMP/2.0' => array('model' => 'CA37'),
                               'KDDI-HI32 UP.Browser/6.2.0.6.2 (GUI) MMP/2.0' => array('model' => 'HI32'),
                               'KDDI-HI33 UP.Browser/6.2.0.7.3.129 (GUI) MMP/2.0' => array('model' => 'HI33'),
                               'KDDI-HI34 UP.Browser/6.2.0.7.3.129 (GUI) MMP/2.0' => array('model' => 'HI34'),
                               'KDDI-HI35 UP.Browser/6.2.0.9.2 (GUI) MMP/2.0' => array('model' => 'HI35'),
                               'KDDI-HI36 UP.Browser/6.2.0.10.4 (GUI) MMP/2.0' => array('model' => 'HI36'),
                               'KDDI-HI37 UP.Browser/6.2.0.10.3.3 (GUI) MMP/2.0' => array('model' => 'HI37'),
                               'KDDI-HI38 UP.Browser/6.2.0.11.1.2 (GUI) MMP/2.0' => array('model' => 'HI38'),
                               'KDDI-HI38 UP.Browser/6.2.0.11.1.3.110 (GUI) MMP/2.0' => array('model' => 'HI38'),
                               'KDDI-HI39 UP.Browser/6.2.0.12.1.3 (GUI) MMP/2.0' => array('model' => 'HI39'),
                               'KDDI-KC22 UP.Browser/6.0.8.3 (GUI) MMP/1.1' => array('model' => 'KC22'),
                               'KDDI-KC23 UP.Browser/6.2.0.5 (GUI) MMP/2.0' => array('model' => 'KC23'),
                               'KDDI-KC27 UP.Browser/6.2.0.9 (GUI) MMP/2.0' => array('model' => 'KC27'),
                               'KDDI-KC28 UP.Browser/6.2.0.10.1 (GUI) MMP/2.0' => array('model' => 'KC28'),
                               'KDDI-KC31 UP.Browser/6.2.0.5 (GUI) MMP/2.0' => array('model' => 'KC31'),
                               'KDDI-KC31 UP.Browser/6.2.0.5.c.1.100 (GUI) MMP/2.0' => array('model' => 'KC31'),
                               'KDDI-KC32 UP.Browser/6.2.0.7.3.129 (GUI) MMP/2.0' => array('model' => 'KC32'),
                               'KDDI-KC33 UP.Browser/6.2.0.9 (GUI) MMP/2.0' => array('model' => 'KC33'),
                               'KDDI-KC34 UP.Browser/6.2.0.7.3.129 (GUI) MMP/2.0' => array('model' => 'KC34'),
                               'KDDI-KC35 UP.Browser/6.2.0.10.2.2 (GUI) MMP/2.0' => array('model' => 'KC35'),
                               'KDDI-KC36 UP.Browser/6.2.0.10.2.2 (GUI) MMP/2.0' => array('model' => 'KC36'),
                               'KDDI-KC37 UP.Browser/6.2.0.11.1.2 (GUI) MMP/2.0' => array('model' => 'KC37'),
                               'KDDI-KC38 UP.Browser/6.2.0.11.1.2 (GUI) MMP/2.0' => array('model' => 'KC38'),
                               'KDDI-KC38 UP.Browser/6.2.0.11.1.2.2 (GUI) MMP/2.0' => array('model' => 'KC38'),
                               'KDDI-KC39 UP.Browser/6.2.0.12.1.3 (GUI) MMP/2.0' => array('model' => 'KC39'),
                               'KDDI-KC3A UP.Browser/6.2.0.12.1.3 (GUI) MMP/2.0' => array('model' => 'KC3A'),
                               'KDDI-KCU1 UP.Browser/6.2.0.5.1 (GUI) MMP/2.0' => array('model' => 'KCU1'),
                               'KDDI-MA31 UP.Browser/6.2.0.11.1.3.110 (GUI) MMP/2.0' => array('model' => 'MA31'),
                               'KDDI-MA31 UP.Browser/6.2.0.11.1.4 (GUI) MMP/2.0' => array('model' => 'MA31'),
                               'KDDI-PT21 UP.Browser/6.2.0.9 (GUI) MMP/2.0' => array('model' => 'PT21'),
                               'KDDI-SA24 UP.Browser/6.0.8.2 (GUI) MMP/1.1' => array('model' => 'SA24'),
                               'KDDI-SA25 UP.Browser/6.2.0.4 (GUI) MMP/2.0' => array('model' => 'SA25'),
                               'KDDI-SA25 UP.Browser/6.2.0.5 (GUI) MMP/2.0' => array('model' => 'SA25'),
                               'KDDI-SA26 UP.Browser/6.2.0.5.1 (GUI) MMP/2.0' => array('model' => 'SA26'),
                               'KDDI-SA27 UP.Browser/6.2.0.6.3 (GUI) MMP/2.0' => array('model' => 'SA27'),
                               'KDDI-SA28 UP.Browser/6.2.0.5 (GUI) MMP/2.0' => array('model' => 'SA28'),
                               'KDDI-SA29 UP.Browser/6.2.0.10.1 (GUI) MMP/2.0' => array('model' => 'SA29'),
                               'KDDI-SA31 UP.Browser/6.2.0.7.3.129 (GUI) MMP/2.0' => array('model' => 'SA31'),
                               'KDDI-SA32 UP.Browser/6.2.0.8 (GUI) MMP/2.0' => array('model' => 'SA32'),
                               'KDDI-SA33 UP.Browser/6.2.0.9 (GUI) MMP/2.0' => array('model' => 'SA33'),
                               'KDDI-SA34 UP.Browser/6.2.0.9.1 (GUI) MMP/2.0' => array('model' => 'SA34'),
                               'KDDI-SA35 UP.Browser/6.2.0.9.1 (GUI) MMP/2.0' => array('model' => 'SA35'),
                               'KDDI-SA36 UP.Browser/6.2.0.10.2.1 (GUI) MMP/2.0' => array('model' => 'SA36'),
                               'KDDI-SA38 UP.Browser/6.2.0.11.1.2 (GUI) MMP/2.0' => array('model' => 'SA38'),
                               'KDDI-SA39 UP.Browser/6.2.0.12.1.3 (GUI) MMP/2.0' => array('model' => 'SA39'),
                               'KDDI-SH31 UP.Browser/6.2.0.10.3.5 (GUI) MMP/2.0' => array('model' => 'SH31'),
                               'KDDI-SH32 UP.Browser/6.2.0.11.2.1 (GUI) MMP/2.0' => array('model' => 'SH32'),
                               'KDDI-SN25 UP.Browser/6.2.0.5 (GUI) MMP/2.0' => array('model' => 'SN25'),
                               'KDDI-SN26 UP.Browser/6.2.0.5 (GUI) MMP/2.0' => array('model' => 'SN26'),
                               'KDDI-SN27 UP.Browser/6.2.0.6.2 (GUI) MMP/2.0' => array('model' => 'SN27'),
                               'KDDI-SN29 UP.Browser/6.2.0.6.2 (GUI) MMP/2.0' => array('model' => 'SN29'),
                               'KDDI-SN31 UP.Browser/6.2.0.7.3.129 (GUI) MMP/2.0' => array('model' => 'SN31'),
                               'KDDI-SN32 UP.Browser/6.2.0.9 (GUI) MMP/2.0' => array('model' => 'SN32'),
                               'KDDI-SN33 UP.Browser/6.2.0.9.2 (GUI) MMP/2.0' => array('model' => 'SN33'),
                               'KDDI-SN34 UP.Browser/6.2.0.10.4 (GUI) MMP/2.0' => array('model' => 'SN34'),
                               'KDDI-SN35 UP.Browser/6.2.0.9.2 (GUI) MMP/2.0' => array('model' => 'SN35'),
                               'KDDI-SN36 UP.Browser/6.2.0.10.4 (GUI) MMP/2.0' => array('model' => 'SN36'),
                               'KDDI-SN37 UP.Browser/6.2.0.11.1.2 (GUI) MMP/2.0' => array('model' => 'SN37'),
                               'KDDI-SN38 UP.Browser/6.2.0.11.2 (GUI) MMP/2.0' => array('model' => 'SN38'),
                               'KDDI-SN39 UP.Browser/6.2.0.11.2.1 (GUI) MMP/2.0' => array('model' => 'SN39'),
                               'KDDI-ST21 UP.Browser/6.0.8.3 (GUI) MMP/1.1' => array('model' => 'ST21'),
                               'KDDI-ST22 UP.Browser/6.0.8.3 (GUI) MMP/1.1' => array('model' => 'ST22'),
                               'KDDI-ST23 UP.Browser/6.2.0.6.2 (GUI) MMP/2.0' => array('model' => 'ST23'),
                               'KDDI-ST24 UP.Browser/6.2.0.8 (GUI) MMP/2.0' => array('model' => 'ST24'),
                               'KDDI-ST25 UP.Browser/6.2.0.8 (GUI) MMP/2.0' => array('model' => 'ST25'),
                               'KDDI-ST26 UP.Browser/6.2.0.8 (GUI) MMP/2.0' => array('model' => 'ST26'),
                               'KDDI-ST27 UP.Browser/6.2.0.9 (GUI) MMP/2.0' => array('model' => 'ST27'),
                               'KDDI-ST28 UP.Browser/6.2.0.10.1 (GUI) MMP/2.0' => array('model' => 'ST28'),
                               'KDDI-ST2C UP.Browser/6.2.0.11.1.2.1 (GUI) MMP/2.0' => array('model' => 'ST2C'),
                               'KDDI-ST31 UP.Browser/6.2.0.11.1.2 (GUI) MMP/2.0' => array('model' => 'ST31'),
                               'KDDI-TS21 UP.Browser/6.0.2.276 (GUI) MMP/1.1' => array('model' => 'TS21'),
                               'KDDI-TS25 UP.Browser/6.0.8.3 (GUI) MMP/1.1' => array('model' => 'TS25'),
                               'KDDI-TS27 UP.Browser/6.2.0.6.2 (GUI) MMP/2.0' => array('model' => 'TS27'),
                               'KDDI-TS28 UP.Browser/6.2.0.6.2 (GUI) MMP/2.0' => array('model' => 'TS28'),
                               'KDDI-TS29 UP.Browser/6.2.0.9 (GUI) MMP/2.0' => array('model' => 'TS29'),
                               'KDDI-TS2A UP.Browser/6.2.0.9 (GUI) MMP/2.0' => array('model' => 'TS2A'),
                               'KDDI-TS2B UP.Browser/6.2.0.9 (GUI) MMP/2.0' => array('model' => 'TS2B'),
                               'KDDI-TS2C UP.Browser/6.2.0.9 (GUI) MMP/2.0' => array('model' => 'TS2C'),
                               'KDDI-TS2D UP.Browser/6.2.0.11.1.2.1 (GUI) MMP/2.0' => array('model' => 'TS2D'),
                               'KDDI-TS31 UP.Browser/6.2.0.8 (GUI) MMP/2.0' => array('model' => 'TS31'),
                               'KDDI-TS32 UP.Browser/6.2.0.9.1 (GUI) MMP/2.0' => array('model' => 'TS32'),
                               'KDDI-TS33 UP.Browser/6.2.0.9.1 (GUI) MMP/2.0' => array('model' => 'TS33'),
                               'KDDI-TS34 UP.Browser/6.2.0.10.2.1 (GUI) MMP/2.0' => array('model' => 'TS34'),
                               'KDDI-TS35 UP.Browser/6.2.0.10.2.1 (GUI) MMP/2.0' => array('model' => 'TS35'),
                               'KDDI-TS36 UP.Browser/6.2.0.10.2.1 (GUI) MMP/2.0' => array('model' => 'TS36'),
                               'KDDI-TS37 UP.Browser/6.2.0.10.3.3 (GUI) MMP/2.0' => array('model' => 'TS37'),
                               'KDDI-TS37 UP.Browser/6.2.0.10.3.3.1 (GUI) MMP/2.0' => array('model' => 'TS37'),
                               'KDDI-TS38 UP.Browser/6.2.0.11.1.2 (GUI) MMP/2.0' => array('model' => 'TS38'),
                               'KDDI-TS39 UP.Browser/6.2.0.11.2 (GUI) MMP/2.0' => array('model' => 'TS39'),
                               'KDDI-TS3A UP.Browser/6.2.0.11.2 (GUI) MMP/2.0' => array('model' => 'TS3A'),
                               'KDDI-TS3A UP.Browser/6.2.0.11.2.1 (GUI) MMP/2.0' => array('model' => 'TS3A'),
                               'KDDI-TS3B UP.Browser/6.2.0.12.1.3 (GUI) MMP/2.0' => array('model' => 'TS3B'),
                               'KDDI-TS3C UP.Browser/6.2.0.12.1.3 (GUI) MMP/2.0' => array('model' => 'TS3C'),
                               'UP.Browser/3.01-HI01 UP.Link/3.4.5.2' => array('model' => 'HI01'),
                               'UP.Browser/3.04-KC14 UP.Link/3.4.5.9' => array('model' => 'KC14'),
                               'UP.Browser/3.04-KC15 UP.Link/3.4.5.9' => array('model' => 'KC15'),
                               'UP.Browser/3.04-KCT8 UP.Link/3.4.5.9' => array('model' => 'KCT8'),
                               'UP.Browser/3.04-ST14 UP.Link/3.4.5.9' => array('model' => 'ST14'),
                               'UP.Browser/3.04-TST4 UP.Link/3.4.5.6' => array('model' => 'TST4')
                               );

    /**#@-*/

    /**#@+
     * @access public
     */

    public function testShouldDetectUserAgentsAsEzweb()
    {
        reset($this->_profiles);
        while (list($userAgent, $profile) = each($this->_profiles)) {
            $agent = new Net_UserAgent_Mobile_EZweb($userAgent);

            $this->assertFalse($agent->isDoCoMo());
            $this->assertTrue($agent->isEZweb());
            $this->assertFalse($agent->isSoftBank());
            $this->assertFalse($agent->isWillcom());
            $this->assertFalse($agent->isNonMobile());
        }
    }

    public function testShouldProvideTheModelNameOfAUserAgent()
    {
        reset($this->_profiles);
        while (list($userAgent, $profile) = each($this->_profiles)) {
            $agent = new Net_UserAgent_Mobile_EZweb($userAgent);

            $this->assertEquals($profile['model'], $agent->getModel());
        }
    }

    /**
     * @since Method available since Release 1.0.0RC1
     */
    public function testShouldProvideTheUidOfASubscriber()
    {
        $uid = '12345678901234_56.ezweb.ne.jp';
        $_SERVER['HTTP_X_UP_SUBNO'] = $uid;
        $agent = new Net_UserAgent_Mobile_EZweb('KDDI-TS3C UP.Browser/6.2.0.12.1.3 (GUI) MMP/2.0');

        $this->assertEquals($uid, $agent->getUID());

        unset($_SERVER['HTTP_X_UP_SUBNO']);
        $agent = new Net_UserAgent_Mobile_EZweb('KDDI-TS3C UP.Browser/6.2.0.12.1.3 (GUI) MMP/2.0');

        $this->assertNull($agent->getUID());
    }

    /**
     * @since Method available since Release 1.0.0RC1
     */
    public function testShouldProvideTheVersionOfAUserAgent()
    {
        $agent = new Net_UserAgent_Mobile_EZweb('KDDI-TS3C UP.Browser/6.2.0.12.1.3 (GUI) MMP/2.0');

        $this->assertEquals('6.2.0.12.1.3 (GUI)', $agent->getVersion());

        $agent = new Net_UserAgent_Mobile_EZweb('UP.Browser/3.04-TST4 UP.Link/3.4.5.6');

        $this->assertEquals('3.04', $agent->getVersion());
    }

    /**
     * @since Method available since Release 1.0.0RC1
     */
    public function testShouldProvideTheDeviceIdOfAUserAgent()
    {
        $agent = new Net_UserAgent_Mobile_EZweb('KDDI-TS3C UP.Browser/6.2.0.12.1.3 (GUI) MMP/2.0');

        $this->assertEquals('TS3C', $agent->getDeviceID());

        $agent = new Net_UserAgent_Mobile_EZweb('UP.Browser/3.04-TST4 UP.Link/3.4.5.6');

        $this->assertEquals('TST4', $agent->getDeviceID());
    }

    /**
     * @since Method available since Release 1.0.0RC1
     */
    public function testShouldProvideTheServerOfAUserAgent()
    {
        $agent = new Net_UserAgent_Mobile_EZweb('KDDI-TS3C UP.Browser/6.2.0.12.1.3 (GUI) MMP/2.0');

        $this->assertEquals('MMP/2.0', $agent->getServer());

        $agent = new Net_UserAgent_Mobile_EZweb('UP.Browser/3.04-TST4 UP.Link/3.4.5.6');

        $this->assertEquals('UP.Link/3.4.5.6', $agent->getServer());
    }

    /**
     * @since Method available since Release 1.0.0RC1
     */
    public function testShouldTellWhetherAUserAgentIsXhtmlCompliantModelOrNot()
    {
        $agent = new Net_UserAgent_Mobile_EZweb('KDDI-TS3C UP.Browser/6.2.0.12.1.3 (GUI) MMP/2.0');

        $this->assertTrue($agent->isXHTMLCompliant());

        $agent = new Net_UserAgent_Mobile_EZweb('UP.Browser/3.04-TST4 UP.Link/3.4.5.6');

        $this->assertFalse($agent->isXHTMLCompliant());
    }

    /**
     * @since Method available since Release 1.0.0RC1
     */
    public function testShouldProvideTheCommentOfAUserAgent()
    {
        $agent = new Net_UserAgent_Mobile_EZweb('UP.Browser/3.04-TS14 UP.Link/3.4.4 (Google WAP Proxy/1.0)');

        $this->assertEquals('Google WAP Proxy/1.0', $agent->getComment());

        $agent = new Net_UserAgent_Mobile_EZweb('KDDI-TS3C UP.Browser/6.2.0.12.1.3 (GUI) MMP/2.0');

        $this->assertNull($agent->getComment());
    }

    /**
     * @since Method available since Release 1.0.0RC1
     */
    public function testShouldTellWhetherAUserAgentIsWap2ModelOrNot()
    {
        $agent = new Net_UserAgent_Mobile_EZweb('KDDI-TS3C UP.Browser/6.2.0.12.1.3 (GUI) MMP/2.0');

        $this->assertTrue($agent->isWAP2());

        $agent = new Net_UserAgent_Mobile_EZweb('UP.Browser/3.04-TST4 UP.Link/3.4.5.6');

        $this->assertFalse($agent->isWAP2());
    }

    /**
     * @since Method available since Release 1.0.0RC1
     */
    public function testShouldTellWhetherAUserAgentIsWap1ModelOrNot()
    {
        $agent = new Net_UserAgent_Mobile_EZweb('KDDI-TS3C UP.Browser/6.2.0.12.1.3 (GUI) MMP/2.0');

        $this->assertFalse($agent->isWAP1());

        $agent = new Net_UserAgent_Mobile_EZweb('UP.Browser/3.04-TST4 UP.Link/3.4.5.6');

        $this->assertTrue($agent->isWAP1());
    }

    /**
     * @since Method available since Release 1.0.0RC1
     */
    public function testShouldProvideTheScreenInformationOfAUserAgent()
    {
        $_SERVER['HTTP_X_UP_DEVCAP_SCREENPIXELS'] = '90,70';
        $_SERVER['HTTP_X_UP_DEVCAP_SCREENDEPTH'] = '16,RGB565';
        $_SERVER['HTTP_X_UP_DEVCAP_ISCOLOR'] = '1';

        $agent = new Net_UserAgent_Mobile_EZweb('KDDI-TS21 UP.Browser/6.0.2.276 (GUI) MMP/1.1');

        $display = $agent->getDisplay();

        $this->assertEquals(90, $display->getWidth());
        $this->assertEquals(70, $display->getHeight());
        $this->assertTrue($display->isColor());
        $this->assertEquals(65536, $display->getDepth());

        unset($_SERVER['HTTP_X_UP_DEVCAP_ISCOLOR']);
        unset($_SERVER['HTTP_X_UP_DEVCAP_SCREENDEPTH']);
        unset($_SERVER['HTTP_X_UP_DEVCAP_SCREENPIXELS']);
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
