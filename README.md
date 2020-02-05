# Moodle Nagios Monitoring [![Build Status](https://travis-ci.org/ffhs/moodle-local_nagios.svg?branch=master)](https://travis-ci.org/ffhs/moodle-local_nagios)

This is a Moodle plugin to simplify monitoring the Moodle service (as opposed to the hardware and infrastructure)
using [Nagios](http://www.nagios.org/). It provides:

1. A simple API which allows plugins to implement "services" which can be monitored.
2. A Nagios plugin script (/cli/check_moodle) which communicates with the Moodle plugin
   to get service status into Nagios.
3. A couple of simple example services.
4. A simple admin page for the plugin available through `Plugins -> Local plugins -> Nagios monitoring`.
   * This simply displays the available services in order for administrators to configure Nagios to check them.

*Note that this plugin is intended for experienced systems administrators who are familiar with both Moodle and Nagios configuration!*

## Documentation (Service API)
The `local\_nagios` plugin scans other Moodle plugins looking for a `db/local_nagios.php` file.

### `db/local_nagios.php`
This should contain a single definition for a variable named "$services", which is an associative array of service name to definition. The definition is a key value pair containing at least the `classname`, and optionally `params`.

*See the plugin's `db/local_nagios.php` file for examples.*

### Service class
The service class must extend the `local\_nagios\service` class and implement the `check\_status()` method.

*The `\local\_nagios\service` object has constants for the status code, e.g. `\local\_nagios\service::NAGIOS\_STATUS\_WARNING`*

## Requirements
* Requires a PHP executable to be available on the server.
* Create or move symlink from `local/nagios/cli/check_moodle` to your Nagios plugins directory (e.g. CentOS `/usr/lib64/nagios/plugins`) with command `ln -s /var/www/html/moodle
/local
/nagios/cli/check_moodle /usr/lib64/nagios/plugins/`.
   * *Additional: Edit the $MOODLE_DIR and (if necessary) the PHP location in the shebang line to reflect your environment.*
* Change the permissions on the script to make it executable (`chmod +x local/nagios/cli/check_moodle`).
* *Optional: Nagios NRPE agent (e.g. CentOS `yum install nagios-plugins-all nrpe`) if Nagios not running on the same server.*

## Installation
Install the plugin like any other plugin to folder `local/nagios`.

Use git to install this plugin: 
```bash
cd /var/www/html/moodle
git clone https://github.com/University-of-Strathclyde-LTE-Team/moodle-local_nagios.git local/nagios
echo '/local/nagios/' >> .git/info/exclude
```

Then complete upgrade over CLI:
```bash
sudo -u apache /usr/bin/php admin/cli/upgrade.php
```
or GUI (Site administration -> Notifications).

## Configuration
### CentOS
Create or update existing NRPE config file (e.g. CentOS `/etc/nrpe.d/nrpe_additional_monitoring.cfg`) and define new commands:

```
command[check_moodle_local_nagios_cron]=/usr/lib64/nagios/plugins/check_moodle -p=local_nagios -s=cron -w=300 -c=3600
command[check_moodle_local_nagios_adhoc_tasks]=/usr/lib64/nagios/plugins/check_moodle -p=local_nagios -s=adhoc_task -w=100 -c=200
command[check_moodle_local_nagios_scheduled_task_updates]=/usr/lib64/nagios/plugins/check_moodle -p=local_nagios -s=scheduled_task -t=\\core\\task\\check_for_updates_task -w=10800 -c=14400
```

*Nagios can now execute the commands with the names in the square brackets*

### Ubuntu
1. Create a new file, moodle.cfg, in your Nagios plugins configuration directory (/etc/nagios-plugins/config).
2. Add a command definition for each Moodle service to be monitored, e.g.:

```
define command{
    command_name    check_moodle_cron
    command_line    /usr/lib/nagios/plugins/check_moodle -p=local_nagios -s=cron
}
```

*Note the use of Moodle-style parameter passing (using =). Use these command in Nagios service definitions as normal.*

## Troubleshooting
### Permission requirements
* The Nagios plugin calls the script `lcoal/nagios/cli/check.php` in the Moodle plugin. This means that the user account Nagios is running under *must* have write access to the
 Moodle data directory, otherwise all checks will result in an error message.
* If the user account Nagios is running under has to use sudo to call the PHP executable, this must be configured in the shebang line in the check_moodle script. The sudo access
 for the account must also be configured to be able to run the PHP executable without being challenged for a password.