/*-------------------------------------------------------------------------------------

FILE INFORMATION

Description: Custom toggle logic for "Meta Options".
Date Created: 2011-07-06.
Author: Cobus, Matty.
Since: 4.3.0


TABLE OF CONTENTS

- Logic for toggling of the "Slide Page" option, depending on page template.

-------------------------------------------------------------------------------------*/jQuery(document).ready(function(){var e="template-biz.php",t="select#page_template",n='select[name="_slide-page"]';jQuery(n).parents("tr").hide();jQuery(t).val()==e&&jQuery(n).parents("tr").show();jQuery(t).change(function(r){jQuery(t).val()==e?jQuery(n).parents("tr").show():jQuery(n).parents("tr").hide()})});