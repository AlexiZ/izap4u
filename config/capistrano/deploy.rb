set :application, 'izap4u'
set :repo_url, "https://github.com/AlexiZ/#{fetch(:application)}.git"

# Default branch is :master
ask :branch, proc { `git rev-parse --abbrev-ref HEAD`.chomp }.call

set :format_options, log_file: 'var/logs/capistrano.log', command_output: false
set :symfony_console_path, "bin/console"
set :linked_dirs, %w{vendor}
set :keep_releases, 3

set :composer_install_flags, "--prefer-dist --no-interaction --no-progress --optimize-autoloader --apcu-autoloader"
