module.exports = function(grunt) {

  // Project configuration.
  grunt.initConfig({
    pkg: grunt.file.readJSON('package.json'),
    
    watch: {
      files: ['src/sass/*.scss', 'src/js/*.js/', 'src/**/*.html', 'src/css/*.css'],
      tasks: ['default'],
      options: {
        nospawn: true
      }
    },
    // Concat
    concat: {
      options: {
        banner: '/* ** <%= pkg.name %> <%= grunt.template.today("yyyy-mm-dd") %> - DO NOT EDIT; FILE AUTO GENERATED ** */\n'
      },
      dist: {
        src: ['src/js/*.js', '!src/js/*-all.js', '!src/js/*-all.min.js'],
        dest: 'src/js/<%= pkg.name %>-all.js',
      },
    },
    // Uglify
    uglify: {
      options: {
        banner: '/* <%= pkg.name %> <%= grunt.template.today("yyyy-mm-dd") %> */\n'
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
        src: ['css/<%= pkg.name %>.min.css'], 
        dest: 'build/',
        cwd: 'src/'
      },
      js: {
        expand: true, 
        src: ['js/<%= pkg.name %>-all.min.js'], 
        dest: 'build/',
        cwd: 'src/'
      },
      html: {
        expand: true, 
        src: ['**/*.html', '!header.html', '!footer.html'], 
        dest: 'build/',
        cwd: 'src/',
        options: {
          process: function(content) {
            var res = content
                        .replace('{{header}}', grunt.file.read('src/header.html'))
                        .replace('{{footer}}', grunt.file.read('src/footer.html'))
            if(res.indexOf('**DEBUG**') < 0) {
                return res.replace('YRM2015-all.js', 'YRM2015-all.min.js')
            }
            return res;
          }
        }
      }
    },
  });

  // Load the plugins task.
  grunt.loadNpmTasks('grunt-contrib-uglify');
  grunt.loadNpmTasks('grunt-contrib-compass');
  grunt.loadNpmTasks('grunt-contrib-copy');
  grunt.loadNpmTasks('grunt-contrib-cssmin');
  grunt.loadNpmTasks('grunt-contrib-concat');
  grunt.loadNpmTasks('grunt-contrib-watch');

  // Default task(s).
  grunt.registerTask('default', ['concat', 'uglify', 'compass', 'cssmin', 'copy']);

};