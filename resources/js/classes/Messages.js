class Messages {

    groups = [];

    callbacks = {};

    constructor(messages = []) {
        this.callbacks = {
            'push': [],
            'prepend': []
        };
        this.groups = this.init(messages);
    }

    init(messages) {
        this.groups = [];
        console.log(messages);
        messages.forEach(message => this.pushMessage(message));

        return this.groups;
    }

    on(type, callback) {
        this.callbacks[type].push(callback);
    }

    getGroups() {
        return this.groups;
    }

    prependMessage(message) {
        let group = this.getFirstGroup();
        if (group === null || (group !== null && !this.isMyGroup(group, message.sender))) {
            group = this.prependGroup(message.sender, this.generateId(), [ message ]);
        } else {
            group.messages.unshift(message);
        }

        this.callbacks['prepend'].forEach(callback => callback.call(this, message, group));
        return true;
    }

    pushMessage(message, refresh = false) {
        let group = this.getLastGroup();
        let sender = this.genSenderId(message);
        if (group === null || (group !== null && !this.isMyGroup(group, sender))) {
            group = this.pushGroup(sender, this.generateId(), [ message ]);
        } else {
            group.messages.push(message);
        }

        if (refresh) {
          this.regenerateIds();
        }

        this.callbacks['push'].forEach(callback => callback.call(this, message, group));
        return true;
    }

    pushGroup(sender, id, messages = []) {
        const group = { id, sender, messages }
        this.groups.push(group);

        return group;
    }

    prependGroup(sender, id, messages = []) {
        const group = { id, sender, messages };
        this.groups.unshift(group);

        return group;
    }

    generateId() {
        const length = 16;
        let result = '';
        const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        const charactersLength = characters.length;
        let counter = 0;
        while (counter < length) {
            result += characters.charAt(Math.floor(Math.random() * charactersLength));
            counter += 1;
        }

        return result;
    }

    getFirstGroup() {
        if (this.groups.length < 1) {
            return null;
        }

        return this.groups[0];
    }

    getLastGroup() {
        if (this.groups.length < 1) {
            return null;
        }

        return this.groups[this.groups.length - 1];
    }

    isMyGroup(group, sender) {
        return group.sender === sender;
    }

    regenerateIds() {
      this.groups = this.groups.map(group => ({...group, id: this.generateId()}));
    }

    genSenderId(message) {
      return message.user_id === null ? `customer_${message.customer_id}` : `agent_${message.user_id}`
    }

}

export { Messages }
