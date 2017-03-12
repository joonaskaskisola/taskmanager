import React from 'react';
import { Link, ReactRouter } from 'react-router';
import { Confirm } from 'semantic-ui-react'
import DefaultApp from './defaultApp.jsx';

export default class MenuApp extends React.Component{
    constructor(props, context) {
        super(props, context);

        this.state = {
            "open": false,
            "result": null
        };

        this.showLogout = this.showLogout.bind(this);
        this.handleLogoutConfirm = this.handleLogoutConfirm.bind(this);
        this.handleLogoutCancel = this.handleLogoutCancel.bind(this);
    }

    showLogout() {
        this.setState({open: true});
    }

    handleLogoutConfirm() {
        this.setState({result: 'confirmed', open: false});

        window.location = '/logout';
    }

    handleLogoutCancel() {
        this.setState({result: 'cancelled', open: false});
    }

    render() {
        return <div>
            <div className="ui menu">
                <div className="ui container">
                    <div className="ui pointing menu">
                        <Link to={"/"} className="item">Taskio</Link>

                        <a className="item" href="#todo">Time tracking</a>

                        <div className="ui simple dropdown item">
                            Entities <i className="dropdown icon"></i>
                            <div className="menu">
                                <Link to={"/customers"} className="item">Customers</Link>
                                <Link to={"/tasks"} className="item">Tasks</Link>
                                <Link to={"/category"} className="item">Categories</Link>
                                <Link to={"/items"} className="item">Items</Link>
                                <Link to={"/unit"} className="item">Units</Link>
                                <Link to={"/country"} className="item">Countries</Link>
                            </div>
                        </div>

                        <div className="right menu">
                            <div className="item right">
                                <Link to={"/inbox"} className="item">
                                    <i className="mail outline icon"></i>
                                </Link>
                            </div>

                            <div className="item right">
                                <Link to={"/profile"} className="item">Profile</Link>
                            </div>

                            <div className="item right" onClick={this.showLogout}>
                                <i className="sign out icon"></i>

                                <Confirm
                                    open={this.state.open}
                                    onCancel={this.handleLogoutCancel}
                                    onConfirm={this.handleLogoutConfirm}
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div className="ui main container">
                {this.props.children}
            </div>
        </div>
    }
};
