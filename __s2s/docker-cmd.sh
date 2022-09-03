#!/bin/sh

# CMD to run when docker container is started

# ..............................................................................
# Init

# host.docker.internal does not resolve when ufw is running for tests
S2S_HOST_IP=$(getent hosts host.docker.internal | awk '{ print $1 }')
if [ -z "$S2S_HOST_IP" ]
then
    # host.docker.internal is only available on docker for mac
    S2S_HOST_IP=$(/sbin/ip route | awk '/default/ { print $3 }')
fi

{
    echo ""
    echo "${S2S_HOST_IP}   host.stock2shop"
} >> /etc/hosts


# ..............................................................................
# Dropbear SSH

CONF_DIR="/etc/dropbear"
SSH_KEY_DSS="${CONF_DIR}/dropbear_dss_host_key"
SSH_KEY_RSA="${CONF_DIR}/dropbear_rsa_host_key"

# Check if conf dir exists
if [ ! -d ${CONF_DIR} ]; then
    mkdir -p ${CONF_DIR}
fi
chown root:root ${CONF_DIR}
chmod 755 ${CONF_DIR}

# Check if keys exists
if [ ! -f ${SSH_KEY_DSS} ]; then
    dropbearkey  -t dss -f ${SSH_KEY_DSS}
fi
chown root:root ${SSH_KEY_DSS}
chmod 600 ${SSH_KEY_DSS}

if [ ! -f ${SSH_KEY_RSA} ]; then
    dropbearkey  -t rsa -f ${SSH_KEY_RSA} -s 2048
fi
chown root:root ${SSH_KEY_RSA}
chmod 600 ${SSH_KEY_RSA}

# -j    Disable local port forwarding
# -k    Disable remote port forwarding
# -s    Disable password logins
# -E    Log to stderr rather than syslog
# -F    Don't fork into background
exec /usr/sbin/dropbear -j -k -s -E -F

