<?php
/**
 * @author Dmitry Ketov <dketov@gmail.com>
 * @license http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @package block
 * @subpackage loginplus
 *
 * Block: Login+ (IDentity Providers support)
 *        It shows IDPs (exported by auth plugins) icons and links 
 *        under 'normal' username/password fields
 */

require_once($CFG->dirroot . '/blocks/login/block_login.php');

class block_loginplus extends block_login {
    function init() {
	parent::init();
        $this->title = get_string('pluginname', 'block_loginplus');
    }

    function get_content () {
      global $SESSION, $OUTPUT, $CFG;

      parent::init();
      parent::get_content();

      if (!isloggedin() or isguestuser()) {

        $authsequence = get_enabled_auth_plugins(true);
        $potentialidps = array();

        foreach($authsequence as $authname) {
          $authplugin = get_auth_plugin($authname);
          $potentialidps = array_merge($potentialidps, 
                           $authplugin->loginpage_idp_list($CFG->wwwroot));
        }

        $idps = '';

        if (!empty($potentialidps)) {
          ob_start();
          include('idps.html');
          $idps .= ob_get_contents();
          ob_end_clean();
        }

        $this->content->text = $idps .= $this->content->text;
      }

      return $this->content;
    }
}
?>
