module.exports = function(grunt) {

  // Project configuration.
  grunt.initConfig({
    pkg: grunt.file.readJSON('package.json'),
    
    watch: {
      files: ['src/**/*.scss', 'src/**/*.js', 'src/**/*.html', 'src/**/*.css', 'src/**/*.php'],
      tasks: ['debug'],
      options: {
        nospawn: true
      }
    },
    // Cleans the build directory
    clean: {
      src: ['build/*']
    },
    // Concat
    concat: {
      options: {
        banner: '/* ** <%= pkg.name %> <%= grunt.template.today("yyyy-mm-dd") %> - DO NOT EDIT; FILE AUTO GENERATED ** */\n'
      },
      dist: {
        src: ['src/js/controller.js', 'src/js/views.js', '!src/js/*-all.js', '!src/js/*-all.min.js'],
        dest: 'src/js/<%= pkg.name %>-all.js',
      },
    },
    // Uglify
    uglify: {
      options: {
        banner: '/* <%= pkg.name %> <%= grunt.template.today("yyyy-mm-dd") %> */\n',
        preserveComments: false
      },
      build: {
        src: 'src/js/<%= pkg.name %>-all.js',
        dest: 'src/js/<%= pkg.name %>-all.min.js'
      }
    },
    // Compass
    compass: {
      dist: {
        options: {
          config: 'config.rb'
        }
      }
    },

    // css min
    cssmin: {
      options: {
        banner: '/* <%= pkg.name %> <%= grunt.template.today("yyyy-mm-dd") %> */'
      },
      build: {
        src: 'src/css/screen.css',
        dest: 'src/css/<%= pkg.name %>.min.css'
      }
    },

    // Copy
    copy: {
      assets: {
        expand: true, 
        src: ['assets/**'], 
        dest: 'build/',
        cwd: 'src/'
      },
      css: {
        expand: true, 
        src: ['css/<%= pkg.name %>.min.css', 'css/tooltipster.css'], 
        dest: 'build/',
        cwd: 'src/'
      },
      js: {
        expand: true, 
        src: ['js/<%= pkg.name %>-all.min.js', 'js/jquery.tooltipster.min.js'], 
        dest: 'build/',
        cwd: 'src/'
      },
      js_debug: {
        expand: true, 
        src: ['js/<%= pkg.name %>-all.js', 'js/jquery.tooltipster.min.js'], 
        dest: 'build/',
        cwd: 'src/'
      },
      php: {
        expand: true,
        src: ['api/**/*.php'],
        dest: 'build/',
        cwd: 'src/'
      },
      html: {
        expand: true, 
        src: ['**/*.html', '!header.html', '!footer.html', '!nav.opaque.html', '!nav.transparent.html'], 
        dest: 'build/',
        cwd: 'src/',
        options: {
          process: function(content) {
            // Add build headers here
            return content
                    .replace('{{header}}', grunt.file.read('src/header.html'))
                    .replace('{{nav.opaque}}', grunt.file.read('src/nav.opaque.html'))
                    .replace('{{nav.transparent}}', grunt.file.read('src/nav.transparent.html'))
                    .replace('{{footer}}', grunt.file.read('src/footer.html'))
                    .replace('YRM2015-all.js', 'YRM2015-all.min.js');
          }
        }
      },
      html_debug: {
        expand: true, 
        src: ['**/*.html', '!header.html', '!footer.html', '!nav.opaque.html', '!nav.transparent.html'], 
        dest: 'build/',
        cwd: 'src/',
        options: {
          process: function(content) {
            return content
                    .replace('{{header}}', grunt.file.read('src/header.html'))
                    .replace('{{nav.opaque}}', grunt.file.read('src/nav.opaque.html'))
                    .replace('{{nav.transparent}}', grunt.file.read('src/nav.transparent.html'))
                    .replace('{{footer}}', grunt.file.read('src/footer.html'));
          }
        }
      },
      misc: {
        expand: true, 
        dot: true,
        src: ['.htaccess', 'api/.htaccess'], 
        dest: 'build/',
        cwd: 'src/'
      }
    },

    'ftp-deploy': {
      build: {
        auth: {
          host: 'yrm2015.co.uk',
          port: 21,
          authKey: 'yrm',
          authPath: '../.ftppass'
        },
        src: 'build/',
        dest: '/httpdocs/',
        exclusions: ['build/.DS_Store'],
        forceVerbose: true
      }
    }
  });

  // Load the plugins.
  grunt.loadNpmTasks('grunt-contrib-uglify');
  grunt.loadNpmTasks('grunt-contrib-compass');
  grunt.loadNpmTasks('grunt-contrib-copy');
  grunt.loadNpmTasks('grunt-contrib-cssmin');
  grunt.loadNpmTasks('grunt-contrib-concat');
  grunt.loadNpmTasks('grunt-contrib-watch');
  grunt.loadNpmTasks('grunt-contrib-clean');
  grunt.loadNpmTasks('grunt-ftp-deploy');

  // Tasks
  grunt.registerTask('build', ['clean', 'concat', 'uglify', 'compass', 'cssmin', 'copy:assets', 'copy:css', 'copy:js', 'copy:html', 'copy:php', 'copy:misc']);
  grunt.registerTask('build-ftp', ['clean', 'concat', 'uglify', 'compass', 'cssmin', 'copy:assets', 'copy:css', 'copy:js', 'copy:html', 'copy:php', 'copy:misc', 'ftp-deploy']);
  grunt.registerTask('debug', ['clean', 'concat', 'compass', 'cssmin', 'copy:assets', 'copy:css', 'copy:js_debug', 'copy:html_debug', 'copy:php', 'copy:misc']);

};