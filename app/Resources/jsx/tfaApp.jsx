import React from 'react';
import { render } from 'react-dom';

export default class TfaApp extends React.Component {
    constructor(props, context) {
        super(props, context);

        this.state = {
            _auth_code: ''
        };

        this.handleChange = this.handleChange.bind(this);
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
                        <form action="/customers" method="post">
                            <div className="field">
                                <label>Two factor authentication key</label>
                                <input placeholder="123 456" value={this.state._auth_code} type="text" id="_auth_code" name="_auth_code" onChange={this.handleChange} />
                            </div>

                            <input type="submit" value="Login" className="ui submit button" />
                        </form>
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
