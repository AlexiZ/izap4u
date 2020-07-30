module.exports = function(grunt) {
    grunt.initConfig({
        sass: {
            dist: {
                files: {
                    'public/styles.css': 'public/assets/main.scss',
                    'public/admin.css': 'public/assets/admin/style.scss'
                }
            }
        },
        cssmin: {
            options: {
                mergeIntoShorthands: false,
                roundingPrecision: -1
            },
            target: {
                files: {
                    'public/styles.min.css': ['public/styles.css'],
                    'public/admin.min.css': ['public/admin.css']
                }
            }
        },
        uglify: {
            site: {
                files: {
                    'public/scripts.min.js': ['public/assets/main.js']
                }
            }
        },
        watch: {
            styles: {
                files: ['public/assets/*.scss'],
                tasks: ['sass', 'cssmin']
            },
            scripts: {
                files: ['public/assets/*.js'],
                tasks: ['uglify']
            }
        }
    });

    grunt.loadNpmTasks('grunt-contrib-sass');
    grunt.loadNpmTasks('grunt-contrib-cssmin');
    grunt.loadNpmTasks('grunt-contrib-uglify-es');
    grunt.loadNpmTasks('grunt-contrib-watch');

    grunt.registerTask('default', ['sass', 'cssmin']);
    grunt.registerTask('prod', ['sass', 'cssmin', 'uglify']);
};