<?php
namespace Concrete\Core\Form\Service\Widget;
use Loader;
use View;
use Request;
class Color {


    /**
     * Creates form fields and JavaScript includes to add a color picker widget.
     * <code>
     *     $dh->output('background-color', '#f00');
     * </code>
     * @param string $fieldFormName
     * @param string $fieldLabel
     * @param string $value
     * @param bool $includeJavaScript
     */
    public function output($inputName, $value = null, $options = array()) {
        $html = '';
        $view = View::getInstance();
        $view->requireAsset('core/colorpicker');
        $form = Loader::helper('form');
        $r = Request::getInstance();
        if ($r->request->has($inputName)) {
            $value = h($r->request->get($inputName));
        }
        $strOptions = '';
        $i = 0;
        $options['value'] = $value;
        $options['className'] = 'ccm-widget-colorpicker';
        $options['showInitial'] = true;
        $options['showInput'] = true;
        $options['cancelText'] = t('Cancel');
        $options['chooseText'] = t('Choose');
        $options['preferredFormat'] = 'rgb';
        $options['clearText'] = t('Clear Color Selection');
        $strOptions = json_encode($options);

        print "<input type=\"text\" name=\"{$inputName}\" value=\"{$value}\" id=\"ccm-colorpicker-{$inputName}\" />";
        print "<script type=\"text/javascript\">";
        print "$(function() { $('#ccm-colorpicker-{$inputName}').spectrum({$strOptions}); })";
        print "</script>";
    }


}
