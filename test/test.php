<?php 
$cmd = '/Users/nozomumorioka/.pyenv/shims/python3 test.py 2>&1';
exec($cmd, $output, $return_var);
print_r($output);
?>