Rake::Task['mysql:load_local'].clear_actions

namespace :mysql do
    task :load_local do
        run_locally do
            username, password, database, host, server_version = get_local_database_config()
            hostcmd = host.nil? ? "" : "-h #{host}"
            execute(
                "mysql",
                "-u '#{username}' --password='#{password}' #{hostcmd}  -e 'DROP DATABASE IF EXISTS `#{database}`'",
                raise_on_non_zero_exit: false
            )
            execute(
                "mysql",
                "-u '#{username}' --password='#{password}' #{hostcmd} -e 'CREATE DATABASE `#{database}` COLLATE utf8_unicode_ci'"
            )
            execute("bzip2 -dkc #{fetch(:db_pull_filename)} > load_local.tmp.sql")
            execute(
                "sh -c \"cat ./load_local.tmp.sql |  mysql -u '#{username}' --password='#{password}' #{hostcmd} #{database}\""
            )
            execute("rm load_local.tmp.sql")
        end
    end
end