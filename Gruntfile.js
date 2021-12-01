module.exports = function(grunt) {
	grunt.initConfig({
		cssmin : {
			target : {
                files: [
					{
						expand: true,
						cwd: 'assets/styles',
						src: [ '*.css', '!*.min.css' ],
						dest: 'assets/styles',
						ext: '.min.css'
					},
					{
						expand: true,
						cwd: 'assets/styles/blocks/core',
						src: [ '*.css', '!*.min.css' ],
						dest: 'assets/styles/blocks/core',
						ext: '.min.css'
					}
				]
			}
		}
	});

	//load cssmin plugin
	grunt.loadNpmTasks('grunt-contrib-cssmin');

	//create default task
	grunt.registerTask('default', ['cssmin' ] );

};
