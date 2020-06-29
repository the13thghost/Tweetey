# Tweetey

Tweetey is a social media clone, intended to mimic the popular features on Twitter. Some of these features include:

- Setting up and updating a user profile
- Following users
- Tweeting with images
- Retweeting with comments
- Replying on tweets/retweets
- Viewing threads

## Requirements

PHP 7.3.4  
Laravel 7.6.2  
MySQL 8.0.15  

## Install

1. Unzip the project
2. Create a new database
3. Rename .env.example file to .env and input your credentials:
```
DB_DATABASE=  
DB_USERNAME=  
DB_PASSWORD=  
FILESYSTEM_DRIVER=public // add at the bottom
```

4. In terminal run: 
```
>> composer install  
>> php artisan key:generate //generates a new APP_KEY  
>> php artisan storage:link  
>> php artisan config:cache  
>> php artisan config:clear
```

5. Migrate the files   
 ```
>> php artisan migrate
 ```

6. Run the development server
```
>> php artisan serve
```

## Screenshots

![Screenshot of project](https://i.imgur.com/ub4ykQe.png)