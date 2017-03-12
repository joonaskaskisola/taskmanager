import React from 'react';
import BaseApp from './components/base-app.jsx';
import { NotificationContainer } from 'react-notifications';
import { List, Segment, Button, Divider } from 'semantic-ui-react'
import { Link } from 'react-router';

export default class DefaultApp extends BaseApp {
    constructor(props, context) {
        super(props, context);

        this.state.app = "default";
    }

    render() {
        return <div className="ui segment">
        <NotificationContainer/>
            <Link to={"/tasks"} className="item">
                <Button primary fluid>View tasks</Button>
            </Link>

            <Divider horizontal>Latest changes</Divider>

            <List divided relaxed>
                <List.Item>
                    <List.Icon name='github' size='large' verticalAlign='middle' />
                    <List.Content>
                        <List.Header as='a'>Semantic-Org/Semantic-UI</List.Header>
                        <List.Description as='a'>Updated 10 mins ago</List.Description>
                    </List.Content>
                </List.Item>
                <List.Item>
                    <List.Icon name='github' size='large' verticalAlign='middle' />
                    <List.Content>
                        <List.Header as='a'>Semantic-Org/Semantic-UI-Docs</List.Header>
                        <List.Description as='a'>Updated 22 mins ago</List.Description>
                    </List.Content>
                </List.Item>
                <List.Item>
                    <List.Icon name='github' size='large' verticalAlign='middle' />
                    <List.Content>
                        <List.Header as='a'>Semantic-Org/Semantic-UI-Meteor</List.Header>
                        <List.Description as='a'>Updated 34 mins ago</List.Description>
                    </List.Content>
                </List.Item>
            </List>
        </div>
    }
}
