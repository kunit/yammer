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
 * @version    CVS: $Id: MobileTestCase.php,v 1.10 2009/05/10 17:28:47 kuboa Exp $
 * @since      File available since Release 0.31.0
 */

require_once dirname(__FILE__) . '/Mobile/AbstractTestCase.php';
require_once 'Net/UserAgent/Mobile.php';

// {{{ Net_UserAgent_MobileTestCase

/**
 * Some tests for Net_UserAgent_Mobile.
 *
 * @category   Networking
 * @package    Net_UserAgent_Mobile
 * @author     KUBO Atsuhiro <kubo@iteman.jp>
 * @copyright  2008-2009 KUBO Atsuhiro <kubo@iteman.jp>
 * @license    http://www.opensource.org/licenses/bsd-license.php  New BSD License
 * @version    Release: 1.0.0
 * @since      Class available since Release 0.31.0
 */
class Net_UserAgent_MobileTestCase extends Net_UserAgent_Mobile_AbstractTestCase
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

    /**#@-*/

    /**#@+
     * @access public
     */

    public function testShouldCreateAnObjectByAGivenUserAgentString()
    {
        $this->assertType('Net_UserAgent_Mobile_DoCoMo',
                          Net_UserAgent_Mobile::factory('DoCoMo/2.0 P904i(c100;TB;W24H15)')
                          );
        $this->assertType('Net_UserAgent_Mobile_EZweb',
                          Net_UserAgent_Mobile::factory('KDDI-SA31 UP.Browser/6.2.0.7.3.129 (GUI) MMP/2.0')
                          );
        $this->assertType('Net_UserAgent_Mobile_SoftBank',
                          Net_UserAgent_Mobile::factory('SoftBank/1.0/706SC/SCJ001 Browser/NetFront/3.3 Profile/MIDP-2.0 Configuration/CLDC-1.1')
                          );
        $this->assertType('Net_UserAgent_Mobile_Willcom',
                          Net_UserAgent_Mobile::factory('Mozilla/3.0(DDIPOCKET;JRC/AH-J3001V,AH-J3002V/1.0/0100/c50)CNF/2.0')
                          );
        $this->assertType('Net_UserAgent_Mobile_NonMobile',
                          Net_UserAgent_Mobile::factory('Mozilla/5.0 (Windows; U; Windows NT 5.1; ja; rv:1.8.1.4) Gecko/20070515 Firefox/2.0.0.4 GoogleToolbarFF 3.0.20070525')
                          );
    }

    public function testShouldCreateAnObjectByTheHttpHeaderInAnEnvironment()
    {
        $_SERVER['HTTP_USER_AGENT'] = 'DoCoMo/2.0 P904i(c100;TB;W24H15)';

        $this->assertType('Net_UserAgent_Mobile_DoCoMo',
                          Net_UserAgent_Mobile::factory()
                          );
    }

    public function testShouldReturnTheExistingObjectIfItExistsBySingletonMethod()
    {
        $agent1 = Net_UserAgent_Mobile::singleton('DoCoMo/2.0 P904i(c100;TB;W24H15)');
        $agent2 = Net_UserAgent_Mobile::singleton('DoCoMo/2.0 P904i(c100;TB;W24H15)');

        $this->assertSame($agent2, $agent1);
    }

    public function testShouldCreateACacheForEachUserAgentIfUsingSingletonMethod()
    {
        $agent1 = Net_UserAgent_Mobile::singleton('DoCoMo/2.0 P904i(c100;TB;W24H15)');
        $agent2 = Net_UserAgent_Mobile::singleton('DoCoMo/1.0/D505i/c20/TB/W20H10');

        $this->assertNotSame($agent2, $agent1);
    }

    public function testShouldSupportFallbackOnNoMatch()
    {
        $ua = 'DoCoMo/1.0/SO504i/abc/TB';
        PEAR::staticPushErrorHandling(PEAR_ERROR_RETURN);
        $agent = Net_UserAgent_Mobile::factory($ua);
        PEAR::staticPopErrorHandling();

        $this->assertTrue(Net_UserAgent_Mobile::isError($agent));
        $this->assertEquals(NET_USERAGENT_MOBILE_ERROR_NOMATCH,
                            $agent->getCode()
                            );

        $GLOBALS['NET_USERAGENT_MOBILE_FallbackOnNomatch'] = true;

        $this->assertType('Net_UserAgent_Mobile_NonMobile',
                          Net_UserAgent_Mobile::factory($ua)
                          );
    }

    public function testShouldTellWhetherAUserAgentIsDocomo()
    {
        $this->assertTrue(Net_UserAgent_Mobile::isDoCoMo('DoCoMo/2.0 P904i(c100;TB;W24H15)'));
    }

    public function testShouldTellWhetherAUserAgentIsEzweb()
    {
        $this->assertTrue(Net_UserAgent_Mobile::isEZweb('KDDI-SA31 UP.Browser/6.2.0.7.3.129 (GUI) MMP/2.0'));
    }

    public function testShouldTellWhetherAUserAgentIsSoftbank()
    {
        $this->assertTrue(Net_UserAgent_Mobile::isSoftBank('SoftBank/1.0/706SC/SCJ001 Browser/NetFront/3.3 Profile/MIDP-2.0 Configuration/CLDC-1.1'));
    }

    public function testShouldTellWhetherAUserAgentIsWillcom()
    {
        $this->assertTrue(Net_UserAgent_Mobile::isWillcom('Mozilla/3.0(DDIPOCKET;JRC/AH-J3001V,AH-J3002V/1.0/0100/c50)CNF/2.0'));
    }

    public function testShouldTellWhetherAUserAgentIsMobile()
    {
        $this->assertTrue(Net_UserAgent_Mobile::isMobile('DoCoMo/2.0 P904i(c100;TB;W24H15)'));
        $this->assertTrue(Net_UserAgent_Mobile::isMobile('KDDI-SA31 UP.Browser/6.2.0.7.3.129 (GUI) MMP/2.0'));
        $this->assertTrue(Net_UserAgent_Mobile::isMobile('SoftBank/1.0/706SC/SCJ001 Browser/NetFront/3.3 Profile/MIDP-2.0 Configuration/CLDC-1.1'));
        $this->assertTrue(Net_UserAgent_Mobile::isMobile('Mozilla/3.0(DDIPOCKET;JRC/AH-J3001V,AH-J3002V/1.0/0100/c50)CNF/2.0'));
        $this->assertFalse(Net_UserAgent_Mobile::isMobile('Mozilla/5.0 (Windows; U; Windows NT 5.1; ja; rv:1.8.1.4) Gecko/20070515 Firefox/2.0.0.4 GoogleToolbarFF 3.0.20070525'));
    }

    public function testShouldTellWhetherAUserAgentIsDocomoByTheHttpHeaderInAnEnvironment()
    {
        $_SERVER['HTTP_USER_AGENT'] = 'DoCoMo/2.0 P904i(c100;TB;W24H15)';

        $this->assertTrue(Net_UserAgent_Mobile::isDoCoMo());
    }

    public function testShouldTellWhetherAUserAgentIsEzwebByTheHttpHeaderInAnEnvironment()
    {
        $_SERVER['HTTP_USER_AGENT'] = 'KDDI-SA31 UP.Browser/6.2.0.7.3.129 (GUI) MMP/2.0';

        $this->assertTrue(Net_UserAgent_Mobile::isEZweb());
    }

    public function testShouldTellWhetherAUserAgentIsSoftbankByTheHttpHeaderInAnEnvironment()
    {
        $_SERVER['HTTP_USER_AGENT'] = 'SoftBank/1.0/706SC/SCJ001 Browser/NetFront/3.3 Profile/MIDP-2.0 Configuration/CLDC-1.1';

        $this->assertTrue(Net_UserAgent_Mobile::isSoftBank());
    }

    public function testShouldTellWhetherAUserAgentIsWillcomByTheHttpHeaderInAnEnvironment()
    {
        $_SERVER['HTTP_USER_AGENT'] = 'Mozilla/3.0(DDIPOCKET;JRC/AH-J3001V,AH-J3002V/1.0/0100/c50)CNF/2.0';

        $this->assertTrue(Net_UserAgent_Mobile::isWillcom());
    }

    public function testShouldTellWhetherAUserAgentIsMobileByTheHttpHeaderInAnEnvironment()
    {
        $_SERVER['HTTP_USER_AGENT'] = 'DoCoMo/2.0 P904i(c100;TB;W24H15)';

        $this->assertTrue(Net_UserAgent_Mobile::isMobile());
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
