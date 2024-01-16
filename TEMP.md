
Instagram
```
caption, id,is_shared_to_feed, media_type,media_url,permalink,thumbnail_url,timestamp,username,children
```

Facebook
```
id,actions,admin_creator,allowed_advertising_objects,application,attachments,backdated_time,call_to_action,can_reply_privately,child_attachments,created_time,feed_targeting,from,full_picture,icon,instagram_eligibility,is_eligible_for_promotion,is_expired,is_hidden,is_instagram_eligible,is_popular,is_published,is_spherical,message,message_tags,parent_id,permalink_url,place,privacy,promotable_id,properties,sheduled_publish_time,shares,status_type,story,story_tags,subscribed,targeting,to,updated_time,video_buying_eligibility,likes.summary(total_count),comments.summary(total_count),reactions.summary(total_count)
```

#TODO
- MetaInstagramAccessToken > MetaInstagramLongAccessToken ?
- save images, crop, compress
- focus point
- business (likes, comments, extra fields..)
- album children images





.btn-social-media-task {
    margin-bottom: 23px;
    height: 38px;
    padding-top: 8px;
}


Add 'Instagram App ID'



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
