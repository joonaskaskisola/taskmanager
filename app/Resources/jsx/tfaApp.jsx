import React from 'react';
import { render } from 'react-dom';
import { Button, Icon } from 'semantic-ui-react'

export default class TfaApp extends React.Component {
    constructor(props, context) {
        super(props, context);

        this.state = {
            _auth_code1: '',
            _auth_code2: '',
            _auth_code3: '',
            _auth_code4: '',
            _auth_code5: '',
            _auth_code6: '',
            errorStyle: {}
        };

        this.handleChange = this.handleChange.bind(this);
        this.submitLogin = this.submitLogin.bind(this);
    }

    handleChange(event) {
        this.state[event.target.name] = event.target.value;
        this.setState(this.state);
    }

    submitLogin() {
        let data = new FormData(), self = this;
        data.append(
            '_auth_code',
            this.state._auth_code1 +
            this.state._auth_code2 +
            this.state._auth_code3 +
            this.state._auth_code4 +
            this.state._auth_code5 +
            this.state._auth_code6
        );

        fetch('/', {method: 'POST', body: data, credentials: 'include'}).then(function(res) {
            if (res.ok === true) {
                location.reload();
            } else {
                self.setState({'errorStyle': {'border': '1px solid red'}})
            }
        });
    }

    render() {
        return <div>
            <div className='ui form inverted segment'>
                <div className='ui main container'>
                    <div className='ui inverted form'>
                        <div className='field' id='tfa'>
                            <label>Two factor authentication key</label>

                            <input
                                maxLength='1'
                                value={this.state._auth_code1}
                                style={this.state.errorStyle}
                                type='text'
                                className='tfa-number'
                                name='_auth_code1'
                                onChange={this.handleChange}/>
                            <input
                                maxLength='1'
                                value={this.state._auth_code2}
                                style={this.state.errorStyle}
                                type='text'
                                className='tfa-number'
                                name='_auth_code2'
                                onChange={this.handleChange}/>

                            <input
                                maxLength='1'
                                value={this.state._auth_code3}
                                style={this.state.errorStyle}
                                type='text'
                                className='tfa-number'
                                name='_auth_code3'
                                onChange={this.handleChange}/>
                            <input
                                maxLength='1'
                                value={this.state._auth_code4}
                                style={this.state.errorStyle}
                                type='text'
                                className='tfa-number'
                                name='_auth_code4'
                                onChange={this.handleChange}/>

                            <input
                                maxLength='1'
                                value={this.state._auth_code5}
                                style={this.state.errorStyle}
                                type='text'
                                className='tfa-number'
                                name='_auth_code5'
                                onChange={this.handleChange}/>
                            <input
                                maxLength='1'
                                value={this.state._auth_code6}
                                style={this.state.errorStyle}
                                type='text'
                                className='tfa-number'
                                name='_auth_code6'
                                onChange={this.handleChange}/>
                        </div>

                        <Button primary={true} onClick={() => {this.submitLogin()}}>
                            <Icon name='unlock' size='small' />
                        </Button>
                    </div>
                </div>
            </div>
        </div>
    }
}

render(
    <TfaApp />,
    document.getElementById('tfaApp')
);
