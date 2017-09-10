import React from 'react';
import { render } from 'react-dom';
import request from 'superagent';
import { Icon } from 'semantic-ui-react'

export default class LoginApp extends React.Component {
	constructor(props, context) {
		super(props, context);

		this.state = {
			_username: this.props.username,
			_password: this.props.password,
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
				_password: this.state._password
			})
			.end(function (err, res) {
				if (!err) {
					window.location = '/';
				} else {
					$('input').transition('tada');
				}
			});
	}

	handleChange(event) {
		this.state[event.target.name] = event.target.value;
		this.setState(this.state);
	}

	render() {
		return <div>
            <div className="ui main container">
                <div className="ui inverted form">
                    <div className="field">
                        <label>Username</label>
                        <input placeholder="Username" value={this.state._username} type="text" id="username" name="_username" onChange={this.handleChange} />
                    </div>

                    <div className="field">
                        <label>Password</label>
                        <input placeholder="hunter" value={this.state._password} type="password" id="password" name="_password" onChange={this.handleChange} />
                    </div>

                    <button type="submit" className="ui submit button" onClick={this.handleSubmit}>
                        <Icon name='sign in' size='small' />
                    </button>
                </div>
            </div>
        </div>
	}
}

render(
    <LoginApp username={ document.getElementById("username").getAttribute('value') } password="moi" />,
	document.getElementById('loginApp')
);
