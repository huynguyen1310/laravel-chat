<template>
  <div>
    <group-chat v-for="group in groups" :group="group" :key="group.id"></group-chat>
  </div>
</template>

<script>
export default {
  props: ['initialGroups', 'user'],
  data() {
    return {
      groups: []
    }
  },
  mounted() {
    this.groups = this.initialGroups;
    Bus.$on('groupCreated', (group) => {
      this.groups.push(group);
    });

    Bus.$on('addUserToGroup', group => {
      let userGroupIds = this.user.groups.map(group => group.id);
      if (!this.user.groups.map(group => group.id).some(id => id === group.id)) {
        console.log('event add user')
        this.groups.push(group);
      }
    })
    this.listenForNewGroups();
    this.listenForAddUserToGroup();
  },
  methods: {
    listenForNewGroups() {
      Echo.private('users.' + this.user.id)
          .listen('GroupCreated', (e) => {
            this.groups.push(e.group);
          });
    },
    listenForAddUserToGroup() {
      Echo.private('users.' + this.user.id)
          .listen('AddUserToGroup', e => {
            this.groups.push(e.group)
          })
    }
  }
}
</script>
