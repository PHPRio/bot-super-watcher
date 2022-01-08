# Super Watcher Bot
Telegram bot for admin groups

[SuperWatcherBot](https://t.me/SuperWatcherBot)

## Production or development

To deploy or run locally this bot, need 2 enviroments to work:

* token to TelegramBot Api
* url to metadata file

After clone the repository and run composer install, run `vendor/bin/phinx migrate`

## How to use

* Add this chat to the private admin group.
  Is the group with admins of group to administrate.
* Add admin rules to bot on admin group
* Access the link that you will see on admin group. Now the bot will be add to group to administrate
* Add admin rules to this bot on group that you want administrate

## Available commands

> **PS**: The commands need run on the administrated group

| command                  | description         |
| ------------------------ | ------------------- |
| `/addrule <ruleName>`    | enable the ban rule |
| `/deleterule <ruleName>` | delete the ban rule |

types of `<ruleName>`

| type        | description                          |
| ----------- | ------------------------------------ |
| ban         | Ban users from group                 |
| deleteAudio | Disable send recorded audio to group |

### ban

The banned users are manually added to [metadata.json]() file by regex or by user object.

Add this bot as admin to your group and wait to any bot join to your group so that it automatically receives ban.

If the name of the user who joined the group match with regex: BAN!

### deleteAudio

The deleted audio will be forwarded to admin group and after will be deleted.