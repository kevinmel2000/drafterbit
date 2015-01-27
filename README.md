# README.md

## What is Drafterbit ?
Drafterbit is self-hosted web app you can use to create a website, yes, like WordPress. Currently still in early phase.

## Requirement
- Web server, Apache httpd 2.2 or newer with mod_rewrite enabled. (you probably can use Nginx but not tested yet).
- PHP 5.4+
- MySQL Server 5.1+

## Install
Drafterbit installation package will be available for download soon. For now you can just do the following step to get it running (I assume you use Ubuntu):

1. Make sure that you have [composer](https://getcomposer.org/) and [bower](https://bower.io/) installed in your computer.
2. Open Terminal then go to web doc root. e.g:
    ```shell
    cd /var/www/
    ```
    
3. Clone this repo (**master** branch)
    ```shell
    git clone https://github.com/drafterbit/drafterbit.git -b master
    ```
    
4. Go to cloned directory then install php dependencies
    ```shell
    cd drafterbit
    composer install --no-div --prefer-dist
    ```
    
5. Then install web dependencies
    ```shell
    bower install
    ```
    
6. From here, you can just visit browser, e.g: `http://localhost/drafterbit` then follow installation instruction.

Feel free to let me know if you get any problem.

## Learn
There is still no documentation at all yet, for now you can just ask me anything anytime thru  this google forum : <https://groups.google.com/forum/#!forum/drafterbit>

## Contribute
Drafterbit is an opensource and intended to be community-driven project. Any kind of contribution (code, translation, stars, bug reports, feature request) are really appreciated.

### Issue Tracker
Issue tracker will not opened until the initial release, if you are doing test then have something to discuss, have a request or any suggestion, please use the google forum above.

### Git Structure
Drafterbit use the git-flow model (see: <https://github.com/nvie/gitflow>) to structure development.

## License
Drafterbit is licensed under the MIT license.