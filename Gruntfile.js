module.exports = function(grunt) {
    grunt.initConfig({
        sass: {
            dist: {
                files: {
                    'public/styles.css': 'public/assets/main.scss'
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
                    'public/styles.min.css': ['public/styles.css']
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
                files: ['public/assets/main.scss'],
                tasks: ['sass', 'cssmin']
            },
            scripts: {
                files: ['public/assets/main.js'],
                tasks: ['uglify']
            }
        }
    });

    grunt.loadNpmTasks('grunt-contrib-sass');
    grunt.loadNpmTasks('grunt-contrib-cssmin');
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-watch');

    grunt.registerTask('default', ['sass', 'cssmin']);
    grunt.registerTask('prod', ['sass', 'cssmin', 'uglify']);
};