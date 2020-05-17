Rake::Task['composer:install_executable'].clear_actions
Rake::Task['composer:run'].clear_actions

namespace :composer do
  task :install_executable do
    on release_roles(fetch(:composer_roles)) do
      within shared_path do
        composer_version = fetch(:composer_version, nil)
        composer_version_option = composer_version ? "-- --version=#{composer_version}" : ""
        if test "[", "!", "-e", "composer.phar", "]"
          execute :curl, "-s", fetch(:composer_download_url), "|", fetch(:composer_php), composer_version_option
        elsif composer_version
          current_version = capture(fetch(:composer_php), "composer.phar", "-V", strip: false)
          unless current_version.include? "Composer version #{composer_version} "
            execute :curl, "-s", fetch(:composer_download_url), "|", fetch(:composer_php), composer_version_option
          end
        end
      end
    end
  end

  task :run, :command do |t, args|
    args.with_defaults(:command => :list)
    on release_roles(fetch(:composer_roles)) do
      within fetch(:composer_working_dir) do
        execute fetch(:composer_php), "#{fetch(:deploy_to)}/shared/composer.phar", args[:command], *args.extras
      end
    end
  end
end
