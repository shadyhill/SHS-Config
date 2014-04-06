module.exports = function(grunt) {

    var path = require('path');

    // extract just the file name from the full path
    var findTemplateName = function (filePath) {
        return filePath
            .replace(grunt.config('templatePath') + '/', '')
            .replace('.hbs', '');
    };

    grunt.config.init({
        pkg: grunt.file.readJSON('package.json'),
        templatePath: 'js/templates',
        lessPath: 'css',

        handlebars: {
            main: {
                options: {
                    namespace: 'Handlebars.templates',
                    processName: findTemplateName
                },
                files: {
                    '<%= templatePath %>/compiled-templates.js': [
                        '<%= templatePath %>/**/*.hbs'
                    ]
                }
            }
        },

        less: {
            main: {
                expand: true,
                cwd: '<%= lessPath %>',
                src: '*.less',
                dest: '<%= lessPath %>',
                ext: '.css'
            }
        },

        watch: {
            handlebars: {
                files: ['<%= templatePath %>/**/*.hbs'],
                tasks: ['handlebars']
            },
            less: {
                files: ['<%= lessPath %>/**/*.less'],
                tasks: ['less']
            }
        }
    });

    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-contrib-handlebars');
    grunt.loadNpmTasks('grunt-contrib-less');
    

    grunt.registerTask('default', ['handlebars', 'less']);
    
};
