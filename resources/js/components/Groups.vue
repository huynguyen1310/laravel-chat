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
      console.log(this.user.groups);
      // this.groups.filter(x => x.id === this.user.groups)
    })
    this.listenForNewGroups();
  },
  methods: {
    listenForNewGroups() {
      Echo.private('users.' + this.user.id)
          .listen('GroupCreated', (e) => {
            this.groups.push(e.group);
          });
    },
    listenForAddUserToGroup() {
      Echo.private('users.' + this.user.id).listen('AddUserToGroup', e => {
        this.groups.push(e.group)
      })
    }
  }
}
</script>
