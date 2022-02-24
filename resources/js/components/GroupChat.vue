<template>
  <div style="margin-bottom: 10px">
    <p>
      <a class="btn btn-primary" data-bs-toggle="collapse" :href="'#collapseOne-' + group.id" role="button"
         aria-expanded="false" aria-controls="collapseExample">
        {{ group.name }}
      </a>
    </p>
    <div class="collapse" :id="'collapseOne-' + group.id">
      <div class="card card-header">
        <form>
          <div class="form-group" style="margin-top: 10px">
            <select v-model="addUsers" multiple id="friends" style="width: 100%">
              <option v-for="user in userAbleToAdd " :value="user.id">
                {{ user.name }}
              </option>
            </select>
          </div>
        </form>
        <button type="submit" class="btn btn-primary" @click.prevent="addUser">Add To Group</button>
      </div>

      <div class="card card-body">
        <ul class="chat">
          <li v-for="conversation in conversations">
            <div class="chat-body clearfix">
              <div class="header">
                <strong class="primary-font">{{ conversation.user.name }}</strong>
              </div>
              <p>
                {{ conversation.message }}
              </p>
            </div>
          </li>
        </ul>
      </div>
      <div class="card card-footer">
        <div class="input-group">
          <input id="btn-input" type="text" class="form-control input-sm" placeholder="Type your message here..."
                 v-model="message" @keydown="isTyping" @keyup.enter="store()" autofocus/>
          <span class="input-group-btn" style="padding-left: 5px">
            <button class="btn btn-warning btn-md" id="btn-chat" @click.prevent="store()">
              Send
            </button>
          </span>
        </div>
        <span v-show="typing" class="help-block" style="font-style: italic;">
           @{{ user }} is typing...
        </span>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: ['group'],
  data() {
    return {
      conversations: [],
      message: '',
      user: '',
      typing: false,
      group_id: this.group.id,
      addUsers: [],
      userAbleToAdd: []
    }
  },
  created() {
    let _this = this;
    Echo.private('groups.' + this.group.id)
        .listenForWhisper('typing', (e) => {
          this.user = e.user;
          this.typing = e.typing;

          // remove is typing indicator after 0.9s
          setTimeout(function () {
            _this.typing = false
          }, 2000);
        });
  },
  mounted() {
    this.listenForNewMessage();
    this.getUsers();
  },
  methods: {
    store() {
      axios.post('/conversations', {message: this.message, group_id: this.group.id})
          .then((response) => {
            this.message = '';
            this.conversations.push(response.data);
          });
    },
    listenForNewMessage() {
      Echo.private('groups.' + this.group.id)
          .listen('NewMessage', (e) => {
            this.conversations.push(e);
          });
    },
    isTyping() {
      let channel = Echo.private('groups.' + this.group.id)

      setTimeout(function () {
        channel.whisper('typing', {
          user: 'someone',
          typing: true
        });
      }, 300);
    },
    getUsers() {
      axios.get('users/add-group/' + this.group.id).then((response) => {
        this.userAbleToAdd = JSON.parse(JSON.stringify(response.data));
      })
    },
    addUser() {
      axios.post(`/groups/${this.group.id}/add-user`, {name: this.name, users: this.addUsers})
          .then((response) => {
            this.addUsers = [];
            Bus.$emit('addUserToGroup', response.data);
          });
    }
  }
}
</script>
