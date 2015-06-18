Block module
============

This module allows to manage HTML blocks. A block is just a name with associated content. 
Once a block is created you can render its content simply by calling `<?php echo $block->render($name); ?>` in a template.

This module is commonly used to manage some kind of contact information (like emails, phones and something like that), and copyright information.