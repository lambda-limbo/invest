#
#
#

all:
	docker build -t unkown .
	docker run -p 0.0.0.0:80:80 unknown
