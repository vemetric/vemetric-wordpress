![Vemetric Wordpress Plugin](https://github.com/user-attachments/assets/781544b0-d1aa-4d43-813d-a5609d20d23b)

# The Vemetric Wordpress Plugin

This is the official Plugin to add Vemetric to your Wordpress site.

Learn more about the Plugin in the [official docs](https://vemetric.com/docs/installation/wordpress).

You can also checkout the package on the [Wordpress Plugin Directory](https://wordpress.org/plugins/vemetric).

## How to Build a Test ZIP

1. Open the `Build Plugin ZIP` GitHub Action
2. Run the workflow from the branch you want to test
3. Download the `vemetric` artifact
4. Upload the downloaded `vemetric.zip` file to your local WordPress instance

## How to Release a new Version

1. Add a new Changelog Entry in the `readme.txt`
2. Start the `Release` GitHub Action
3. Download the .zip file from the GitHub Action Artifacts
4. Test the changes on a local Wordpress Instance
5. Submit the changes to SVN as described [here](https://developer.wordpress.org/plugins/wordpress-org/how-to-use-subversion/#editing-existing-files).
