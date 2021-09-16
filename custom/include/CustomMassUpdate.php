<?php
// Extension of class MassUpdate to allow MassUpdate of field of type TextArea

if (! defined('sugarEntry') || ! sugarEntry)
    die('Not A Valid Entry Point');

require_once('include/MassUpdate.php');

class CustomMassUpdate extends MassUpdate {

    /**
     * Override of this method to allow MassUpdate of field of type TextArea
     * @param string $displayname field label
     * @param string $field field name
     * @param bool $even even or odd
     * @return string html field data
     */
    protected function addDefault($displayname, $field, &$even) {
        if ($field["type"] == 'text' || $field["type"]=='varchar') {
            $even = ! $even;
            $varname = $field["name"];
            $displayname = addslashes($displayname);
            $html = <<<EOQ
    <td scope="row" width="20%">$displayname</td>
    <td class="dataField" width="30%"><textarea name="$varname" style="width: 90%;" id="mass_{$varname}"></textarea></td>
EOQ;
            return $html;
        }
        else
            return '';
    }

}
?>