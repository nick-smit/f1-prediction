import Layout from '@/Layouts/Layout';
import React, { ReactElement } from 'react';
import { Text } from '@chakra-ui/react';

export default function Home(): ReactElement {
    return (
        <Layout title={'Home'}>
            <Text>
                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris
                semper faucibus luctus. Mauris semper purus ut ultrices
                consectetur. Proin accumsan lacinia est eu dapibus. Nam metus
                neque, sollicitudin consectetur efficitur non, tincidunt vitae
                justo. Proin elementum et ex facilisis rhoncus. Quisque porta
                augue ac elit consequat egestas. Nullam in ante ut est commodo
                egestas.
            </Text>
            <Text>
                Ut nec arcu nunc. Suspendisse tempor elit sed ultrices
                ultricies. In blandit nulla at leo convallis, ut sodales odio
                condimentum. Aenean semper ligula sed quam volutpat euismod.
                Vestibulum eget nulla id est efficitur fermentum. Maecenas
                ultricies, arcu ut imperdiet interdum, ex justo laoreet risus,
                at pulvinar lorem dui sed ligula. Aliquam hendrerit diam at
                lectus posuere, id tristique nisi maximus. Donec egestas luctus
                leo hendrerit commodo. Donec viverra euismod rutrum. Donec
                malesuada sodales ligula non molestie. Nullam semper justo vel
                arcu scelerisque fringilla. Sed vitae purus quis velit malesuada
                aliquet. Vivamus placerat pulvinar turpis, quis iaculis odio
                accumsan et. Fusce aliquet ex non venenatis auctor.
            </Text>
            <Text>
                Aenean tempor justo quis tempus suscipit. Morbi cursus magna ut
                tempor luctus. Integer non massa faucibus, placerat arcu
                imperdiet, condimentum enim. Vestibulum pellentesque arcu in
                sollicitudin venenatis. Pellentesque sapien odio, posuere in
                venenatis sed, finibus id eros. Suspendisse porttitor iaculis
                porta. Fusce quis venenatis magna. Sed eget eros id orci pretium
                efficitur vitae eu felis. Duis ex urna, auctor eu purus ac,
                pretium pretium neque. Praesent lacus nunc, feugiat et eros
                vitae, commodo mollis est. Donec sed ex aliquet, vulputate purus
                vitae, iaculis quam.
            </Text>
            <Text>
                Mauris congue ac libero et ullamcorper. Pellentesque fringilla
                pretium augue, at posuere enim accumsan eu. Donec lobortis, nisi
                vitae condimentum tincidunt, lacus metus malesuada dui, vel
                facilisis nunc risus at metus. Praesent posuere semper
                vestibulum. Sed id nisi massa. Aenean ultricies sodales leo, nec
                elementum lorem aliquam eu. Integer sit amet auctor quam, vitae
                condimentum ex. Curabitur nec placerat lacus. Vivamus risus
                neque, ullamcorper a ligula sed, suscipit gravida ex. Phasellus
                suscipit vehicula arcu, interdum cursus felis elementum ut.
                Nulla facilisi. Praesent pretium, tellus quis viverra bibendum,
                ante mauris sollicitudin sem, et vestibulum sapien purus id
                elit. Orci varius natoque penatibus et magnis dis parturient
                montes, nascetur ridiculus mus. Aliquam auctor eu ante non
                rutrum. Nunc dignissim id metus ac tincidunt. Sed sed efficitur
                nisl.
            </Text>
            <Text>
                Curabitur ut nibh at diam convallis aliquet faucibus vel metus.
                Pellentesque iaculis turpis turpis, vel aliquam turpis faucibus
                a. Nunc dolor massa, scelerisque eget lectus eget, porttitor
                malesuada risus. Morbi molestie ligula vitae arcu tempus
                scelerisque. Suspendisse facilisis erat vitae nulla pharetra
                suscipit. Nunc dictum erat condimentum sollicitudin accumsan.
                Quisque massa nisl, elementum vel viverra at, faucibus a metus.
                In auctor, quam non egestas lacinia, mauris felis viverra metus,
                ut maximus sapien sapien vitae quam. Sed gravida ligula non
                magna malesuada efficitur. Sed ultricies magna nibh, quis
                efficitur arcu egestas et. Sed suscipit gravida velit quis
                porttitor. Quisque imperdiet dui leo, ac finibus magna iaculis
                ac. Mauris suscipit eu ligula nec faucibus. Duis semper maximus
                nisi, sit amet dictum lacus eleifend vel. Aliquam vel leo
                ullamcorper, imperdiet urna non, convallis mi. Quisque laoreet
                mi ut ligula lobortis faucibus.
            </Text>
            {/*<Stack spacing={4}>*/}
            {/*    <Box as={'header'}>*/}
            {/*        <Heading>Welcome to MyDarts</Heading>*/}
            {/*    </Box>*/}
            {/*    <Box as={'main'}>*/}
            {/*        <Stack spacing={2}>*/}
            {/*            <Text>*/}
            {/*                GrandPrixGuessr is currently in development. If you*/}
            {/*                have any suggestions or feedback, please let me know*/}
            {/*                through the feedback form .*/}
            {/*            </Text>*/}
            {/*        </Stack>*/}
            {/*    </Box>*/}
            {/*</Stack>*/}
        </Layout>
    );
}
