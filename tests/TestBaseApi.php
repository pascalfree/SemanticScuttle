<?php
/**
 * SemanticScuttle - your social bookmark manager.
 *
 * PHP version 5.
 *
 * @category Bookmarking
 * @package  SemanticScuttle
 * @author   Christian Weiske <cweiske@cweiske.de>
 * @license  GPL http://www.gnu.org/licenses/gpl.html
 * @link     http://sourceforge.net/projects/semanticscuttle
 */

/**
 * Base unittest class for web API tests.
 *
 * @category Bookmarking
 * @package  SemanticScuttle
 * @author Christian Weiske <cweiske@cweiske.de>
 * @license  GPL http://www.gnu.org/licenses/gpl.html
 * @link     http://sourceforge.net/projects/semanticscuttle
 */
class TestBaseApi extends TestBase
{
    protected $url;
    protected $urlPart = null;

    /**
     * @var SemanticScuttle_Service_User
     */
    protected $us;

    /**
     * @var SemanticScuttle_Service_Bookmark
     */
    protected $bs;



    protected function setUp()
    {
        if ($GLOBALS['unittestUrl'] === null) {
            $this->assertTrue(false, 'Unittest URL not set in config');
        }
        if ($this->urlPart === null) {
            $this->assertTrue(false, 'Set the urlPart variable');
        }
        $this->url = $GLOBALS['unittestUrl'] . $this->urlPart;

        $this->us = SemanticScuttle_Service_Factory::get('User');
        $this->us->deleteAll();
        $this->bs = SemanticScuttle_Service_Factory::get('Bookmark');
        $this->bs->deleteAll();
        $this->b2t = SemanticScuttle_Service_Factory::get('Bookmark2Tag');
        $this->b2t->deleteAll();
    }



    /**
     * Gets a HTTP request object.
     * Uses $this->url plus $urlSuffix as request URL.
     *
     * @param string $urlSuffix Suffix for the URL
     *
     * @return HTTP_Request2 HTTP request object
     *
     * @uses $url
     */
    protected function getRequest($urlSuffix = null)
    {
        $req = new HTTP_Request2(
            $this->url . $urlSuffix,
            HTTP_Request2::METHOD_GET
        );

        return $req;
    }



    /**
     * Creates a user and a HTTP request object and prepares
     * the request object with authentication details, so that
     * the user is logged in.
     *
     * @param string $urlSuffix Suffix for the URL
     * @param mixed  $auth      If user authentication is needed (true/false)
     *                          or array with username and password
     *
     * @return array(HTTP_Request2, integer) HTTP request object and user id
     *
     * @uses getRequest()
     */
    protected function getAuthRequest($urlSuffix = null, $auth = true)
    {
        $req = $this->getRequest($urlSuffix);
        if (is_array($auth)) {
            list($username, $password) = $auth;
        } else {
            $username = 'testuser';
            $password = 'testpassword';
        }
        $uid = $this->addUser($username, $password);
        $req->setAuth(
            $username, $password,
            HTTP_Request2::AUTH_BASIC
        );
        return array($req, $uid);
    }

}
?>
