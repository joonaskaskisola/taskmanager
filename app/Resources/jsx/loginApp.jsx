import React from 'react';
import { render } from 'react-dom';
import request from 'superagent';

export default class LoginApp extends React.Component {
    constructor(props, context) {
        super(props, context);

        this.state = {
            _username: this.props.children,
            errors: []
        };

        this.handleSubmit = this.handleSubmit.bind(this);
        this.handleChange = this.handleChange.bind(this);
    }

    handleSubmit(event) {
        event.preventDefault();

        request
            .post("/login")
            .send({
                _username: this.state._username,
                _password: 'moi'
            })
            .end(function (err, res) {
                if (!err) {
                    window.location = '/customers';
                } else {
                    location.reload();
                }
            });
    }

    handleChange(event) {
        this.state[event.target.name] = event.target.value;
        this.setState(this.state);
    }

    render() {
        return <div>
            <div className="ui form inverted segment">
                <div className="ui main container">
                    <div className="ui inverted form">
                        <div className="field">
                            <label>Username</label>
                            <input placeholder="Username" value={this.state._username} type="text" id="username" name="_username" onChange={this.handleChange} />
                        </div>

                        <div className="field">
                            <label>Password</label>
                            <input readOnly placeholder="hunter" value="moi" type="password" id="password" name="_password" />
                        </div>

                        <button type="submit" className="ui submit button" onClick={this.handleSubmit}>Submit</button>
                    </div>
                </div>
            </div>
        </div>
    }
}

render(
    <LoginApp>
        { document.getElementById("username").getAttribute('value') }
    </LoginApp>,
    document.getElementById('loginApp')
);
