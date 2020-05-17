set :deploy_config_path, "config/capistrano/deploy.rb"
set :stage_config_path, "config/capistrano/stages/"
set :upload_files_path, "config/capistrano/files/"

# Load DSL and Setup Up Stages
require 'capistrano/setup'

# Includes default deployment tasks
require 'capistrano/deploy'

# Prevent deprecation notice
require "capistrano/scm/git"
install_plugin Capistrano::SCM::Git

require 'capistrano/file-permissions'
require 'capistrano/composer'
require 'capistrano/symfony'
require 'capistrano/copy_files'
require 'capistrano/lephare'
require 'capistrano/pending'

# Loads custom tasks from `lib/capistrano/tasks' if you have any defined.
Dir.glob('config/capistrano/tasks/*.rake').each { |r| import r }
