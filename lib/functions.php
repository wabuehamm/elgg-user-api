<?php

use Elgg\Filesystem\MimeTypeDetector;

/**
 * Create a new user
 *
 * @param $params array Parameters (see start.php for valid parameters)
 * @return bool Success
 * @throws IOException
 * @throws InvalidParameterException
 */
function user_create($params)
{
    try {
        $guid = register_user(
            elgg_extract('username', $params),
            elgg_extract('password', $params),
            elgg_extract('displayName', $params),
            elgg_extract('email', $params),
            true
        );
    } catch (RegistrationException $e) {
        return false;
    }

    if ($guid) {
        $new_user = get_entity($guid);

        $profile_manager_json = elgg_extract('profileManagerJson', $params);
        if ($profile_manager_json != null) {
            $profile_manager = json_decode($profile_manager_json, true);
            if ($profile_manager != null) {
                foreach ($profile_manager as $name => $value) {
                    $new_user->$name = $value;
                }
            }

        }

        $new_user->admin_created = TRUE;
        $new_user->created_by_guid = elgg_get_logged_in_user_guid();

        $new_user->language = elgg_extract('language', $params);

        $avatar = elgg_extract('avatar', $params);

        if ($avatar) {
            $tmp_filename = time() . elgg_extract('username', $params);
            $file = new ElggFile();
            $file->owner_guid = $new_user->guid;
            $file->setFilename("tmp/$tmp_filename");
            $file->open("write");
            $file->write(base64_decode($avatar));
            $file->close();
            $file->mimetype = (new MimeTypeDetector())->getType($tmp_filename, "image/png");
            $file->simpletype = elgg_get_file_simple_type($file->mimetype);
            $new_user->saveIconFromElggFile($file);
        }


        if (elgg_extract('notifyUser', $params)) {
            $subject = elgg_echo('useradd:subject');
            $body = elgg_echo('useradd:body', array(
                elgg_extract('displayName', $params),
                elgg_get_site_entity()->name,
                elgg_get_site_entity()->url,
                elgg_extract('username', $params),
                elgg_extract('password', $params)
            ),
                $new_user->language
            );

            try {
                $current_view = elgg_get_viewtype();
                elgg_set_viewtype("default");
                notify_user(
                    $new_user->guid,
                    elgg_get_site_entity()->guid,
                    $subject,
                    $body,
                    [
                        "object" => $new_user,
                        "action" => "useradd",
                        "password" => elgg_extract('password', $params)
                    ]
                );
                elgg_set_viewtype($current_view);
            } catch (NotificationException $e) {
                return false;
            }
        }

        return true;
    } else {
        return false;
    }
}

/**
 * Delete a user by its username
 * @param $username string Username of user to delete
 * @return bool
 */

function user_delete($username)
{
    $user = get_user_by_username($username);
    if ($user) {
        $user->delete();
        return true;
    } else {
        return false;
    }

}
