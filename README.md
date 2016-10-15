# The okvpn.org site code

| Branches      | Builds                                                                                                                                            |
| ------------- |:------------------------------------------------------------------------------------------------------------------------------------------------- |
| master        | [![The Build Status](https://fr1.jurk.xyz/Jurasikt/okvpn.org/badges/master/build.svg)](https://fr1.jurk.xyz/Jurasikt/okvpn.org/commits/master)    |
| develop       | [![The Build Status](https://fr1.jurk.xyz/Jurasikt/okvpn.org/badges/develop/build.svg)](https://fr1.jurk.xyz/Jurasikt/okvpn.org/commits/develop)  |


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