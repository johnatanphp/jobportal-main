#!/bin/bash

# Collect some information about this instance
MY_ID=$(curl -s http://169.254.169.254/latest/meta-data/instance-id)
MY_REGION=$(curl -s http://169.254.169.254/latest/meta-data/placement/availability-zone | sed 's/.$//')
MY_ASG=$(/usr/local/bin/aws autoscaling describe-auto-scaling-instances --region $MY_REGION --instance-ids $MY_ID --query "AutoScalingInstances[].AutoScalingGroupName" --output text)

# Query the ASG
FIRST_ID=$(/usr/local/bin/aws autoscaling describe-auto-scaling-groups --region $MY_REGION --auto-scaling-group-name "${MY_ASG}" --query "AutoScalingGroups[].Instances[0].InstanceId" --output text)

if [ "$FIRST_ID" = "$MY_ID" ];
then
    php /var/www/html/jobportal/index.php console/Cron init
else
    echo "no"
fi
