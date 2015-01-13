# README.md

## What is Drafterbit ?
Drafterbit is self-hosted web app you can use to create a website, yes, like WordPress. Currently still in early phase and slow development.

## Requirement
- Web server, Apache httpd 2.2 or newer with mod_rewrite enabled. (you probably can use Nginx but not tested yet).
- PHP 5.4+
- MySQL Server 5.1+

## Install
Drafterbit installation package will be available for download as soon as possible. For now you can just do the following step to get it running (I assume you use Ubuntu):

1. Make sure that you have [composer](https://getcomposer.org/) and [bower](https://bower.io/) installed in your computer.
2. Open Terminal then go to web doc root. e.g:
    ```shell
    cd /var/www/
    ```
    
3. Clone this repo
    ```shell
    git clone https://github.com/drafterbit/drafterbit.git
    ```
    
4. Go to cloned directory then install php dependencies:
    ```shell
    cd drafterbit
    composer install --no-div --prefer-dist
    ```
    
5. Wait, then install web dependencies
    ```shell
    bower install
    ```
    
6. From here, you can visit browser, e.g: `http://localhost/drafterbit` then follow installation instruction.

Feel free to let me know if you get any problem.

## Learn
There is still no documentation at all yet, but for now you can just ask me anything anytime thru google group : <https://groups.google.com/forum/#!forum/drafterbit>

## Contribute
Drafterbit is an opensource and intended to be community-driven project. Any kind of contribution (code, translation, stars, bug reports, feature request) are really appreciated.

## License
Drafterbit is licensed under the MIT license.