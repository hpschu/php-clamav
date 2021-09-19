FROM debian:buster-slim
RUN apt-get update && apt-get install -y clamav clamav-daemon
COPY clamd.conf /etc/clamav/clamd.conf
COPY start-clamav.sh start-clamav.sh
RUN mkdir -p /var/run/clamav
RUN chown clamav:clamav /var/run/clamav && chmod 750 /var/run/clamav
RUN chown clamav:clamav start-clamav.sh /etc/clamav /etc/clamav/clamd.conf /etc/clamav/freshclam.conf && chmod u+x start-clamav.sh
# use this when testing is done
# USER clamav
EXPOSE 3310
CMD ["/start-clamav.sh"]

