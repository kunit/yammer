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
 * @version    CVS: $Id: WillcomTestCase.php,v 1.6 2009/05/10 17:28:48 kuboa Exp $
 * @since      File available since Release 0.31.0
 */

require_once dirname(__FILE__) . '/AbstractTestCase.php';
require_once 'Net/UserAgent/Mobile/Willcom.php';

// {{{ Net_UserAgent_Mobile_WillcomTestCase

/**
 * Some tests for Net_UserAgent_Mobile_Willcom.
 *
 * @category   Networking
 * @package    Net_UserAgent_Mobile
 * @author     KUBO Atsuhiro <kubo@iteman.jp>
 * @copyright  2008-2009 KUBO Atsuhiro <kubo@iteman.jp>
 * @license    http://www.opensource.org/licenses/bsd-license.php  New BSD License
 * @version    Release: 1.0.0
 * @since      Class available since Release 0.31.0
 */
class Net_UserAgent_Mobile_WillcomTestCase extends Net_UserAgent_Mobile_AbstractTestCase
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

    private $_profiles = array('Mozilla/3.0(DDIPOCKET;JRC/AH-J3001V,AH-J3002V/1.0/0100/c50)CNF/2.0' => array('model' => 'AH-J3001V,AH-J3002V'),
                               'Mozilla/3.0(DDIPOCKET;KYOCERA/AH-K3001V/1.7.2.70.000000/0.1/C100) Opera 7.0' => array('model' => 'AH-K3001V'),
                               'Mozilla/3.0(DDIPOCKET;JRC/AH-J3003S/1.0/0100/c50)CNF/2.0' => array('model' => 'AH-J3003S'),
                               'Mozilla/3.0(WILLCOM;SANYO/WX310SA/2;1/1/C128) NetFront/3.3,61.198.142.127' => array('model' => 'WX310SA')
                               );

    /**#@-*/

    /**#@+
     * @access public
     */

    public function testShouldDetectUserAgentsAsWillcom()
    {
        reset($this->_profiles);
        while (list($userAgent, $profile) = each($this->_profiles)) {
            $agent = new Net_UserAgent_Mobile_Willcom($userAgent);

            $this->assertFalse($agent->isDoCoMo());
            $this->assertFalse($agent->isEZweb());
            $this->assertFalse($agent->isSoftBank());
            $this->assertTrue($agent->isWillcom());
            $this->assertFalse($agent->isNonMobile());
        }
    }

    public function testShouldProvideTheModelNameOfAUserAgent()
    {
        reset($this->_profiles);
        while (list($userAgent, $profile) = each($this->_profiles)) {
            $agent = new Net_UserAgent_Mobile_Willcom($userAgent);

            $this->assertEquals($profile['model'], $agent->getModel());
        }
    }

    /**
     * @since Method available since Release 1.0.0RC1
     */
    public function testShouldProvideTheVendorNameOfAUserAgent()
    {
        $agent = new Net_UserAgent_Mobile_Willcom('Mozilla/3.0(WILLCOM;SANYO/WX310SA/2;1/1/C128) NetFront/3.3,61.198.142.127');

        $this->assertEquals('SANYO', $agent->getVendor());
    }

    /**
     * @since Method available since Release 1.0.0RC1
     */
    public function testShouldProvideTheModelVersionOfAUserAgent()
    {
        $agent = new Net_UserAgent_Mobile_Willcom('Mozilla/3.0(WILLCOM;SANYO/WX310SA/2;1/1/C128) NetFront/3.3,61.198.142.127');

        $this->assertEquals('2;1', $agent->getModelVersion());
    }

    /**
     * @since Method available since Release 1.0.0RC1
     */
    public function testShouldProvideTheBrowserVersionOfAUserAgent()
    {
        $agent = new Net_UserAgent_Mobile_Willcom('Mozilla/3.0(WILLCOM;SANYO/WX310SA/2;1/1/C128) NetFront/3.3,61.198.142.127');

        $this->assertEquals('1', $agent->getBrowserVersion());
    }

    /**
     * @since Method available since Release 1.0.0RC1
     */
    public function testShouldProvideTheCacheSizeOfAUserAgent()
    {
        $agent = new Net_UserAgent_Mobile_Willcom('Mozilla/3.0(WILLCOM;SANYO/WX310SA/2;1/1/C128) NetFront/3.3,61.198.142.127');

        $this->assertEquals(128, $agent->getCacheSize());
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
