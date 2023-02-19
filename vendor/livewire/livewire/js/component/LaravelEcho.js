import store from '@/Store'

export default function () {
    store.registerHook('component.initialized', component => {
        if (Array.isArray(component.listeners)) {
            component.listeners.forEach(event => {
                if (event.startsWith('echo')) {
                    if (typeof Echo === 'undefined') {
                        console.warn('Laravel Echo cannot be found')
                        return
                    }

                    let event_parts = event.split(/(echo:|echo-)|:|,/)

                    if (event_parts[1] == 'echo:') {
                        event_parts.splice(2, 0, 'channel', undefined)
                    }

                    if (event_parts[2] == 'notification') {
                        event_parts.push(undefined, undefined)
                    }

                    let [
                        s1,
                        signature,
                        channel_type,
                        s2,
                        channel,
                        s3,
                        event_name,
                    ] = event_parts

                    if (['channel', 'private', 'encryptedPrivate'].includes(channel_type)) {
                        Echo[channel_type](channel).listen(event_name, e => {
                            store.emit(event, e)
                        })
                    } else if (channel_type == 'presence') {
                        if (['here', 'joining', 'leaving'].includes(event_name)) {
                            Echo.join(channel)[event_name](e => {
                                store.emit(event, e)
                            })
                        }else{
                            Echo.join(channel).listen(event_name, e => {
                                store.emit(event, e)
                            })
                        }
                    } else if (channel_type == 'notification') {
                        Echo.private(channel).notification(notification => {
                            store.emit(event, notification)
                        })
                    } else {
                        console.warn('Echo channel type not yet supported')
                    }
                }
            })
        }
    })
}
