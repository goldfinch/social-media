
#TODO
- MetaInstagramAccessToken > MetaInstagramLongAccessToken ?
- save images, crop, compress
- focus point
- business (likes, comments, extra fields..)
- album children images




1) (! NOT REQUIRED) Get 'Instagram App ID'

https://developers.facebook.com/apps/*/instagram-basic-display/basic-display/


2) Get 'Instagram App Secret'

https://developers.facebook.com/apps/*/instagram-basic-display/basic-display/

3) Get token (User Token Generator)

https://developers.facebook.com/apps/*/instagram-basic-display/basic-display/

'Add or Remove Instagram Testers'

4) Set fields

caption, id,is_shared_to_feed, media_type,media_url,permalink,thumbnail_url,timestamp,username,children

5) Set limit

10

6) Sync posts

7) Set Cron tasks:
/dev/tasks/SocialMediaRefresh once in 4 weeks
/dev/tasks/SocialMediaSync once in hour (or as you desire)



/opt/alt/php74/usr/bin/php /home/UU/public_html/vendor/silverstripe/framework/cli-script.php dev/tasks/SocialCache
