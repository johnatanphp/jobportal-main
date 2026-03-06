<?php
// Script to set up the environment
if (!file_exists('ca_app/config/database.php')) {
    copy('ca_app/config/database.example.php', 'ca_app/config/database.php');
}
if (!file_exists('ca_app/config/constants.php')) {
    copy('ca_app/config/constants.example.php', 'ca_app/config/constants.php');
}
if (!file_exists('public/uploads/tmp')) {
    mkdir('public/uploads/tmp', 0755, true);
}
echo "Setup complete\n";
?>
