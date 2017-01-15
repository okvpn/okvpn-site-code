# The okvpn.org site code 

[![The Build Status](https://travis-ci.org/okvpn/okvpn-site-code.svg?branch=master)](https://travis-ci.org/okvpn/okvpn-site-code)  [![SensioLabsInsight](https://insight.sensiolabs.com/projects/61edaf5b-8b1d-4980-b354-d7217bc28849/mini.png)](https://insight.sensiolabs.com/projects/61edaf5b-8b1d-4980-b354-d7217bc28849)

| Branches      | Private Builds                                                                                                                                    |
| ------------- |:------------------------------------------------------------------------------------------------------------------------------------------------- |
| master        | [![build status](https://git.yandex.ovh/root/okvpn/badges/master/build.svg)](https://git.yandex.ovh/root/okvpn/commits/master)    |
| develop       | [![build status](https://git.yandex.ovh/root/okvpn/badges/develop/build.svg)](https://git.yandex.ovh/root/okvpn/commits/develop)  |


## Installation


```bash
git clone https://github.com/okvpn/okvpn-site-code.git
cd okvpn-site-code
composer install
```

And run migrations
```bash
cd applivation
phinx migrate
```

## Contributing Changes to the Code

Read Conventions & Code Version Control

* See [Code Version Control](doc/cvs.md) for details
* See [Code style](doc/code_style.md) for details

## license

GPLv3, see [https://www.gnu.org/licenses/gpl-3.0.txt](https://www.gnu.org/licenses/gpl-3.0.txt) for details